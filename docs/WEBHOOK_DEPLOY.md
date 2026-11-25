# GitHub Webhook 自动部署指南

## 概述

本指南帮助你配置 GitHub Webhook，实现代码推送到 GitHub 后自动在服务器上同步主题目录。

```
本地 → GitHub (push) → Webhook → 服务器 (git fetch + git reset --hard)
```

> **安全提示**：`deploy/webhook.php` v2.0 已移除硬编码密钥与 `git clean -fd`。部署时请通过环境变量或受 `.gitignore` 排除的 `webhook.local.php` 提供密钥与仓库路径；`includes/acf/` 等未跟踪私有目录会在同步时被完整保留。

---

## 第一步：服务器准备

### 1. 生成强密钥

至少需要 16 字节以上随机字符串（推荐 32 字节）：

```bash
openssl rand -hex 32
```

### 2. 部署脚本与配置

将 `deploy/webhook.php` 放到可访问入口（不建议在仓库目录内直接暴露未配置保护的入口）：

```bash
cp /path/to/your/site/wp-content/themes/subo2026/deploy/webhook.php /path/to/your/site/webhook.php
chmod 640 /path/to/your/site/webhook.php
```

支持以下**两种配置方式**（环境变量优先级高于本地配置文件）：

#### 方式 A：通过 Nginx / PHP-FPM 环境变量注入（推荐）

在 Nginx `location = /webhook.php` 或 PHP-FPM pool 中注入：

```nginx
location = /webhook.php {
    # 可选：限制 GitHub Webhook IP 段
    # allow 140.82.112.0/20;
    # allow 143.55.64.0/20;
    # allow 185.199.108.0/22;
    # allow 192.30.252.0/22;
    # deny all;

    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    fastcgi_param SUBO4_WEBHOOK_SECRET "<你的随机密钥>";
    fastcgi_param SUBO4_WEBHOOK_BRANCH "main";
    fastcgi_param SUBO4_WEBHOOK_REPO_PATH "/path/to/your/site/wp-content/themes/subo2026";
    fastcgi_param SUBO4_WEBHOOK_LOG_FILE "/path/to/your/site/webhook.log";
    fastcgi_pass unix:/var/run/php/php-fpm.sock;
}
```

#### 方式 B：使用同目录私有文件 `webhook.local.php`

```bash
cp /path/to/your/site/wp-content/themes/subo2026/deploy/webhook.local.php.example /path/to/your/site/webhook.local.php
chmod 600 /path/to/your/site/webhook.local.php
```

按模板填入 `secret_token`、`branch`、`repo_path` 与 `log_file` 即可（`deploy/*.local.php` 已在 `.gitignore` 中排除）。

### 3. 设置日志与目录权限

```bash
touch /path/to/your/site/webhook.log
chmod 640 /path/to/your/site/webhook.log
chown www-data:www-data /path/to/your/site/webhook.php /path/to/your/site/webhook.log

# 确保 Web 用户对主题 Git 目录有读写权限
chown -R www-data:www-data /path/to/your/site/wp-content/themes/subo2026
```

> 建议通过 Nginx 配置禁止外部直接访问 `webhook.log` 与 `webhook.local.php`（例如 `location ~ /(webhook\.log|webhook\.local\.php)$ { deny all; }`）。

---

## 第二步：配置 GitHub Webhook

1. 打开 GitHub 仓库的 **Settings → Webhooks → Add webhook**
2. 填写表单：
   - **Payload URL**：`https://example.com/webhook.php`
   - **Content type**：`application/json`
   - **Secret**：填入第一步生成的随机密钥
   - **SSL verification**：启用（`Enable SSL verification`）
   - **Events**：选择 `Just the push event`
   - **Active**：勾选启用
3. 点击 **Add webhook**。GitHub 会自动发送 `ping` 事件，正常应收到 `200` `{"success":true,"message":"Pong"}`。

---

## 第三步：机制与故障排查

### 内置安全机制

- **Fail-Closed 密钥检查**：密钥未配置、少于 16 位或为占位符时返回 `500`。
- **HMAC-SHA256 常量时间验签**：使用 `hash_equals()` 验证 `X-Hub-Signature-256`，失败返回 `403`。
- **并发锁**：基于 `flock` 对同仓库部署加排他锁，并发触发返回 `409 Conflict`。
- **保护未跟踪私有文件**：仅执行 `git -C <repo> fetch --prune origin <branch>` 与 `git -C <repo> reset --hard origin/<branch>`，绝不运行 `git clean -fd`，确保服务器上的 `includes/acf/` 等私有文件不会丢失。
- **响应脱敏**：HTTP 响应仅返回简洁状态，详细路径、提交计数与 Git 命令输出仅写入服务端 `webhook.log`。

### 常见问题

| 现象 | 常见原因与处理 |
|---|---|
| 返回 `405 Method Not Allowed` | 浏览器直接 GET 访问了接口；GitHub Webhook 必须使用 `POST` |
| 返回 `500 Webhook secret is not configured` | 未注入 `SUBO4_WEBHOOK_SECRET` / 未配置 `webhook.local.php`，或密钥长度小于 16 位 |
| 返回 `500 Repository path is not configured` | `repo_path` 不存在或缺少 `.git` 目录 |
| 返回 `403 Invalid signature` | GitHub Secret 与服务器配置不一致 |
| 返回 `409` | 上一次部署尚未结束，稍后在 GitHub Recent Deliveries 重试即可 |
| 返回 `500 Deployment failed` | 查看服务端 `webhook.log` 中的 `Git 输出`，多为 Web 用户权限或远端拉取鉴权问题 |

---

## 备选方案：定时拉取（无公网 Webhook 入口时）

```bash
crontab -e
# 每 5 分钟同步一次跟踪文件（同样不使用 git clean）
*/5 * * * * git -C /path/to/your/site/wp-content/themes/subo2026 fetch origin main && git -C /path/to/your/site/wp-content/themes/subo2026 reset --hard origin/main >> /var/log/auto-deploy.log 2>&1
```
