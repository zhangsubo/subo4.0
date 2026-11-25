# 快速开始：GitHub Webhook 自动部署

## 5 分钟快速配置

> 建议优先通过环境变量配置密钥与路径；若使用本地文件配置，请将 `webhook.local.php.example` 复制为 `webhook.local.php`（受 `.gitignore` 排除），不要直接把密钥硬编码进 `webhook.php`。

### 1️⃣ 生成随机密钥

```bash
openssl rand -hex 32
```

保存输出的随机串到你的密钥管理器中（切勿明文提交到仓库）。

### 2️⃣ 上传脚本并配置环境

```bash
# SSH 到服务器，复制脚本到站点可访问目录（或直接映射）
cp /path/to/your/site/wp-content/themes/subo2026/deploy/webhook.php /path/to/your/site/webhook.php
cp /path/to/your/site/wp-content/themes/subo2026/deploy/webhook.local.php.example /path/to/your/site/webhook.local.php
chmod 640 /path/to/your/site/webhook.php
chmod 600 /path/to/your/site/webhook.local.php
```

编辑服务器上的 `webhook.local.php`（或通过 Nginx `fastcgi_param` / PHP-FPM 注入 `SUBO4_WEBHOOK_SECRET`、`SUBO4_WEBHOOK_BRANCH`、`SUBO4_WEBHOOK_REPO_PATH`、`SUBO4_WEBHOOK_LOG_FILE`）：

```php
return array(
    'secret_token' => '', // 填入步骤 1 生成的密钥
    'branch'       => 'main',
    'repo_path'    => '/path/to/your/site/wp-content/themes/subo2026',
    'log_file'     => __DIR__ . '/webhook.log',
);
```

### 3️⃣ 设置日志与 Git 目录权限

```bash
touch /path/to/your/site/webhook.log
chmod 640 /path/to/your/site/webhook.log
chown www-data:www-data /path/to/your/site/webhook.php /path/to/your/site/webhook.local.php /path/to/your/site/webhook.log

# 确保 Web 用户可以更新主题仓库
chown -R www-data:www-data /path/to/your/site/wp-content/themes/subo2026
```

### 4️⃣ 配置 GitHub Webhook

1. 打开仓库的 **Settings → Webhooks → Add webhook**
2. 填写：
   - **Payload URL**：`https://example.com/webhook.php`
   - **Content type**：`application/json`
   - **Secret**：填入步骤 1 生成的密钥
   - **Events**：选择 `Just the push event`
3. 保存后，GitHub 发送的 `ping` 事件会收到 `200` `{"success":true,"message":"Pong"}`。

### 5️⃣ 特性与安全边界

- **未配置密钥时拒绝运行**：密钥为空、长度不足 16 位或为占位符时返回 `500`（fail-closed）。
- **并发文件锁**：内置 `flock` 排他锁，并发请求返回 `409`。
- **保护私有目录**：仅执行 `git fetch` + `git reset --hard origin/<branch>`，**不会执行 `git clean -fd`**，确保服务器上的未跟踪本地配置与私有目录不被清理。
- **脱敏响应**：详细 Git 输出仅记入服务端 `webhook.log`，HTTP 响应不回显服务器路径与命令 stdout。

---

**完整文档**：[docs/WEBHOOK_DEPLOY.md](../docs/WEBHOOK_DEPLOY.md)
