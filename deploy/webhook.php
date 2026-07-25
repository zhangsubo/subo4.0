<?php
/**
 * GitHub Webhook 自动部署脚本
 *
 * 当 GitHub 仓库收到 push 事件时，自动在服务器上执行 git pull
 *
 * @package SUBO4_Block_Theme
 * @version 1.0.0
 */

// 配置
define('SECRET_TOKEN', 'YOUR_SECRET_TOKEN_HERE'); // 替换为你的密钥
define('BRANCH', 'main'); // 要自动部署的分支
define('REPO_PATH', '/path/to/your/theme/directory'); // 主题目录的绝对路径
define('LOG_FILE', __DIR__ . '/webhook.log'); // 日志文件路径

/**
 * 记录日志
 */
function log_message($message) {
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[{$timestamp}] {$message}" . PHP_EOL;
    file_put_contents(LOG_FILE, $log_entry, FILE_APPEND);
}

/**
 * 验证 GitHub 签名
 */
function verify_signature($payload, $signature) {
    if (empty($signature)) {
        return false;
    }

    $expected_signature = 'sha256=' . hash_hmac('sha256', $payload, SECRET_TOKEN);
    return hash_equals($expected_signature, $signature);
}

/**
 * 执行 Git 命令
 */
function execute_git_pull() {
    $output = [];
    $return_var = 0;

    // 记录执行的命令
    log_message("仓库路径: " . REPO_PATH);
    log_message("目标分支: " . BRANCH);

    // 检查目录是否存在
    if (!is_dir(REPO_PATH)) {
        log_message("错误: 仓库目录不存在");
        return [
            'success' => false,
            'output' => 'Repository path does not exist: ' . REPO_PATH,
            'exit_code' => 1
        ];
    }

    // 检查是否是 Git 仓库
    if (!is_dir(REPO_PATH . '/.git')) {
        log_message("错误: 不是有效的 Git 仓库");
        return [
            'success' => false,
            'output' => 'Not a valid git repository: ' . REPO_PATH,
            'exit_code' => 1
        ];
    }

    // 切换到仓库目录并执行 git pull
    $commands = [
        "cd " . escapeshellarg(REPO_PATH),
        "git fetch origin " . escapeshellarg(BRANCH) . " 2>&1",
        "git reset --hard origin/" . escapeshellarg(BRANCH) . " 2>&1",
        "git clean -fd 2>&1"
    ];

    $command = implode(' && ', $commands);
    log_message("执行命令: " . $command);

    exec($command, $output, $return_var);

    // 记录详细输出
    $output_str = implode("\n", $output);
    log_message("命令退出码: " . $return_var);
    log_message("命令完整输出: " . ($output_str ?: '(无输出)'));

    return [
        'success' => $return_var === 0,
        'output' => $output_str,
        'exit_code' => $return_var
    ];
}

// 主逻辑
try {
    // 获取请求内容
    $payload = file_get_contents('php://input');
    $signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

    log_message("收到 Webhook 请求");

    // 验证签名
    if (!verify_signature($payload, $signature)) {
        http_response_code(403);
        log_message("签名验证失败");
        die(json_encode(['error' => 'Invalid signature']));
    }

    log_message("签名验证成功");

    // 解析 payload
    $data = json_decode($payload, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        log_message("JSON 解析失败");
        die(json_encode(['error' => 'Invalid JSON']));
    }

    // 检查是否是 push 事件
    if (!isset($_SERVER['HTTP_X_GITHUB_EVENT']) || $_SERVER['HTTP_X_GITHUB_EVENT'] !== 'push') {
        http_response_code(200);
        log_message("非 push 事件，跳过部署");
        die(json_encode(['message' => 'Not a push event']));
    }

    // 检查分支
    $ref = $data['ref'] ?? '';
    if ($ref !== 'refs/heads/' . BRANCH) {
        http_response_code(200);
        log_message("非目标分支 ({$ref})，跳过部署");
        die(json_encode(['message' => 'Not the target branch']));
    }

    // 获取提交信息
    $commits = $data['commits'] ?? [];
    $commit_messages = array_map(function($commit) {
        return $commit['message'] ?? '';
    }, $commits);

    log_message("目标分支 push 事件，开始部署");
    log_message("提交数量: " . count($commits));
    log_message("提交信息: " . implode('; ', $commit_messages));

    // 执行部署
    $result = execute_git_pull();

    if ($result['success']) {
        http_response_code(200);
        log_message("部署成功");
        log_message("Git 输出: " . $result['output']);

        echo json_encode([
            'success' => true,
            'message' => 'Deployment successful',
            'output' => $result['output']
        ]);
    } else {
        http_response_code(500);
        log_message("部署失败 (退出码: {$result['exit_code']})");
        log_message("Git 输出: " . $result['output']);

        echo json_encode([
            'success' => false,
            'message' => 'Deployment failed',
            'output' => $result['output'],
            'exit_code' => $result['exit_code']
        ]);
    }

} catch (Exception $e) {
    http_response_code(500);
    log_message("异常: " . $e->getMessage());

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
