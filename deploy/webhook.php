<?php
/**
 * GitHub Webhook 自动部署脚本
 *
 * 当 GitHub 仓库收到目标分支 push 事件时，在服务器上同步代码（不清理未跟踪的私有目录）。
 *
 * @package SUBO4_Classic_Theme
 * @version 2.0.0
 */

header( 'Content-Type: application/json; charset=utf-8' );

/**
 * 读取非空环境变量
 *
 * @param string $name Environment variable name.
 * @return string
 */
function subo4_webhook_env( $name ) {
    $value = getenv( $name );
    if ( false === $value && isset( $_ENV[ $name ] ) ) {
        $value = $_ENV[ $name ];
    }
    if ( false === $value && isset( $_SERVER[ $name ] ) ) {
        $value = $_SERVER[ $name ];
    }
    return is_string( $value ) ? trim( $value ) : '';
}

/**
 * 加载部署配置（优先级：环境变量 > deploy/webhook.local.php > 空安全默认）
 *
 * @return array
 */
function subo4_webhook_load_config() {
    $local = array();
    $local_file = __DIR__ . '/webhook.local.php';
    if ( is_file( $local_file ) && is_readable( $local_file ) ) {
        $loaded = include $local_file;
        if ( is_array( $loaded ) ) {
            $local = $loaded;
        }
    }

    $pick = static function ( $env_key, $local_key, $default = '' ) use ( $local ) {
        $env_val = subo4_webhook_env( $env_key );
        if ( '' !== $env_val ) {
            return $env_val;
        }
        if ( isset( $local[ $local_key ] ) && is_string( $local[ $local_key ] ) && '' !== trim( $local[ $local_key ] ) ) {
            return trim( $local[ $local_key ] );
        }
        return $default;
    };

    return array(
        'secret_token' => $pick( 'SUBO4_WEBHOOK_SECRET', 'secret_token', '' ),
        'branch'       => $pick( 'SUBO4_WEBHOOK_BRANCH', 'branch', 'main' ),
        'repo_path'    => $pick( 'SUBO4_WEBHOOK_REPO_PATH', 'repo_path', '' ),
        'log_file'     => $pick( 'SUBO4_WEBHOOK_LOG_FILE', 'log_file', __DIR__ . '/webhook.log' ),
    );
}

/**
 * 写入服务端部署日志（过滤换行以防日志注入）
 *
 * @param string $log_file Log file path.
 * @param string $message  Log message.
 */
function subo4_webhook_log( $log_file, $message ) {
    $timestamp = gmdate( 'Y-m-d H:i:s' ) . ' UTC';
    $safe_msg  = str_replace( array( "\r", "\n" ), ' ', (string) $message );
    $entry     = "[{$timestamp}] {$safe_msg}" . PHP_EOL;
    @file_put_contents( $log_file, $entry, FILE_APPEND | LOCK_EX );
}

/**
 * 输出脱敏后的 JSON 响应
 *
 * @param int   $status  HTTP status code.
 * @param array $payload Response payload.
 */
function subo4_webhook_respond( $status, array $payload ) {
    http_response_code( $status );
    echo json_encode( $payload, JSON_UNESCAPED_UNICODE );
}

/**
 * 校验分支名合法性，防止参数注入
 *
 * @param string $branch Git branch name.
 * @return bool
 */
function subo4_webhook_is_valid_branch( $branch ) {
    if ( '' === $branch || 0 === strpos( $branch, '-' ) || false !== strpos( $branch, '..' ) ) {
        return false;
    }
    return 1 === preg_match( '/^[A-Za-z0-9._\/-]+$/', $branch );
}

/**
 * 执行远端同步（仅 fetch + reset --hard，严禁 git clean 以保护 includes/acf/ 等私有目录）
 *
 * @param string $repo_path Target repository directory.
 * @param string $branch    Target branch name.
 * @param string $log_file  Log file path.
 * @return array
 */
function subo4_webhook_execute_sync( $repo_path, $branch, $log_file ) {
    $output     = array();
    $return_var = 0;

    $repo_arg   = escapeshellarg( $repo_path );
    $branch_arg = escapeshellarg( $branch );
    $remote_ref = escapeshellarg( 'origin/' . $branch );

    $command = sprintf(
        'git -C %s fetch --prune origin %s 2>&1 && git -C %s reset --hard %s 2>&1',
        $repo_arg,
        $branch_arg,
        $repo_arg,
        $remote_ref
    );

    subo4_webhook_log( $log_file, "执行同步: branch={$branch} repo={$repo_path}" );
    exec( $command, $output, $return_var );

    $output_str = implode( "\n", $output );
    subo4_webhook_log( $log_file, '命令退出码: ' . $return_var );
    if ( '' !== $output_str ) {
        subo4_webhook_log( $log_file, 'Git 输出: ' . $output_str );
    }

    return array(
        'success'   => ( 0 === $return_var ),
        'exit_code' => $return_var,
    );
}

/**
 * 处理 Webhook HTTP 请求
 */
function subo4_webhook_handle_request() {
    $config       = subo4_webhook_load_config();
    $log_file     = $config['log_file'];
    $secret_token = $config['secret_token'];
    $branch       = $config['branch'];
    $repo_path    = $config['repo_path'];

    $method = isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( (string) $_SERVER['REQUEST_METHOD'] ) : 'GET';
    if ( 'POST' !== $method ) {
        subo4_webhook_respond( 405, array( 'success' => false, 'error' => 'Method Not Allowed' ) );
        return;
    }

    // Fail-closed：未配置密钥或密钥过短/仍为占位符时拒绝服务
    $placeholders = array( 'YOUR_SECRET_TOKEN_HERE', 'CHANGE_ME' );
    if ( '' === $secret_token || strlen( $secret_token ) < 16 || in_array( $secret_token, $placeholders, true ) ) {
        subo4_webhook_log( $log_file, '配置错误: Webhook secret 未配置或长度不足 16 位' );
        subo4_webhook_respond( 500, array( 'success' => false, 'error' => 'Webhook secret is not configured' ) );
        return;
    }

    $payload   = (string) file_get_contents( 'php://input' );
    $signature = isset( $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ) ? (string) $_SERVER['HTTP_X_HUB_SIGNATURE_256'] : '';

    $expected_signature = 'sha256=' . hash_hmac( 'sha256', $payload, $secret_token );
    if ( '' === $signature || ! hash_equals( $expected_signature, $signature ) ) {
        subo4_webhook_log( $log_file, '拒绝请求: HMAC 签名校验失败' );
        subo4_webhook_respond( 403, array( 'success' => false, 'error' => 'Invalid signature' ) );
        return;
    }

    $data = json_decode( $payload, true );
    if ( JSON_ERROR_NONE !== json_last_error() || ! is_array( $data ) ) {
        subo4_webhook_log( $log_file, '拒绝请求: JSON 解析失败' );
        subo4_webhook_respond( 400, array( 'success' => false, 'error' => 'Invalid JSON payload' ) );
        return;
    }

    $event = isset( $_SERVER['HTTP_X_GITHUB_EVENT'] ) ? (string) $_SERVER['HTTP_X_GITHUB_EVENT'] : '';
    if ( 'ping' === $event ) {
        subo4_webhook_log( $log_file, '收到 ping 事件，签名校验通过' );
        subo4_webhook_respond( 200, array( 'success' => true, 'message' => 'Pong' ) );
        return;
    }

    if ( 'push' !== $event ) {
        subo4_webhook_log( $log_file, "忽略非 push 事件: {$event}" );
        subo4_webhook_respond( 200, array( 'success' => true, 'message' => 'Event ignored' ) );
        return;
    }

    if ( ! subo4_webhook_is_valid_branch( $branch ) ) {
        subo4_webhook_log( $log_file, "配置错误: 分支名非法 ({$branch})" );
        subo4_webhook_respond( 500, array( 'success' => false, 'error' => 'Invalid branch configuration' ) );
        return;
    }

    $ref = isset( $data['ref'] ) && is_string( $data['ref'] ) ? $data['ref'] : '';
    if ( 'refs/heads/' . $branch !== $ref ) {
        subo4_webhook_log( $log_file, "跳过非目标分支: {$ref}" );
        subo4_webhook_respond( 200, array( 'success' => true, 'message' => 'Not the target branch' ) );
        return;
    }

    if ( '' === $repo_path || 0 === strpos( $repo_path, '/path/to/' ) || ! is_dir( $repo_path ) || ! file_exists( $repo_path . '/.git' ) ) {
        subo4_webhook_log( $log_file, '配置错误: 仓库目录无效或不存在 .git' );
        subo4_webhook_respond( 500, array( 'success' => false, 'error' => 'Repository path is not configured' ) );
        return;
    }

    $lock_path = rtrim( sys_get_temp_dir(), '/\\' ) . '/subo4-webhook-' . md5( $repo_path ) . '.lock';
    $lock_fp   = @fopen( $lock_path, 'c' );
    if ( ! $lock_fp || ! flock( $lock_fp, LOCK_EX | LOCK_NB ) ) {
        if ( $lock_fp ) {
            fclose( $lock_fp );
        }
        subo4_webhook_log( $log_file, '跳过部署: 存在正在执行中的部署任务' );
        subo4_webhook_respond( 409, array( 'success' => false, 'error' => 'Another deployment is in progress' ) );
        return;
    }

    try {
        $commits = isset( $data['commits'] ) && is_array( $data['commits'] ) ? $data['commits'] : array();
        subo4_webhook_log( $log_file, '目标分支 push 事件，提交数: ' . count( $commits ) );

        $result = subo4_webhook_execute_sync( $repo_path, $branch, $log_file );
        if ( $result['success'] ) {
            subo4_webhook_log( $log_file, '部署成功' );
            subo4_webhook_respond( 200, array( 'success' => true, 'message' => 'Deployment successful' ) );
        } else {
            subo4_webhook_log( $log_file, '部署失败 (退出码: ' . $result['exit_code'] . ')' );
            subo4_webhook_respond( 500, array( 'success' => false, 'error' => 'Deployment failed' ) );
        }
    } finally {
        flock( $lock_fp, LOCK_UN );
        fclose( $lock_fp );
    }
}

if ( ! defined( 'SUBO4_WEBHOOK_NO_AUTO_RUN' ) ) {
    subo4_webhook_handle_request();
}
