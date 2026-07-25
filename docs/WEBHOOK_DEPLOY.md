# GitHub Webhook 自动部署指南

## 概述

本指南帮助你配置 GitHub Webhook，实现代码推送到 GitHub 后自动在服务器上执行 `git pull`。

## 部署架构

```
本地 → GitHub (push) → Webhook → 服务器 (git pull)
```

## 第一步：服务器准备

### 1. 上传 Webhook 脚本

将 `deploy/webhook.php` 上传到服务器的可访问目录：

```bash
# 方法 1: 使用 scp
scp deploy/webhook.php user@server:/path/to/your/site/webhook.php

# 方法 2: 通过 Git 部署
ssh user@server
cd /path/to/your/site/wp-content/themes/subo2026
git pull origin main
cp deploy/webhook.php /path/to/your/site/webhook.php
```

**推荐路径**：`/path/to/your/site/webhook.php`（网站根目录）

**访问 URL**：`https://example.com/webhook.php`

### 2. 生成密钥

生成一个强密码作为 Webhook 密钥：

```bash
# 方法 1: 使用 openssl
openssl rand -hex 32

# 方法 2: 使用 Python
python3 -c "import secrets; print(secrets.token_hex(32))"

# 方法 3: 在线生成
# 访问 https://randomkeygen.com/
```

示例输出：
```
a3f9d8e7c6b5a4f3e2d1c0b9a8f7e6d5c4b3a2f1e0d9c8b7a6f5e4d3c2b1a0f9
```

**保存这个密钥**，后面会用到。

### 3. 配置脚本

编辑服务器上的 `webhook.php`：

```bash
ssh user@server
nano /path/to/your/site/webhook.php
```

修改以下配置：

```php
// 将生成的密钥粘贴到这里
define('SECRET_TOKEN', 'a3f9d8e7c6b5a4f3e2d1c0b9a8f7e6d5c4b3a2f1e0d9c8b7a6f5e4d3c2b1a0f9');

// 确认分支名称（通常是 main）
define('BRANCH', 'main');

// 确认主题目录路径
define('REPO_PATH', '/path/to/your/site/wp-content/themes/subo2026');

// 日志文件路径（确保有写权限）
define('LOG_FILE', '/path/to/your/site/webhook.log');
```

### 4. 设置文件权限

```bash
# 设置 webhook.php 可执行
chmod 644 /path/to/your/site/webhook.php

# 创建并设置日志文件权限
touch /path/to/your/site/webhook.log
chmod 666 /path/to/your/site/webhook.log

# 如果使用 Nginx + PHP-FPM
chown www-data:www-data /path/to/your/site/webhook.php
chown www-data:www-data /path/to/your/site/webhook.log

# 如果使用 Apache
chown apache:apache /path/to/your/site/webhook.php
chown apache:apache /path/to/your/site/webhook.log
```

### 5. 配置 Git 权限

确保 Web 服务器用户可以执行 git 命令：

```bash
# 切换到主题目录
cd /path/to/your/site/wp-content/themes/subo2026

# 设置目录所有者（根据你的服务器配置）
chown -R www-data:www-data .

# 或者使用 sudo 配置（更安全）
# 编辑 sudoers 文件
visudo

# 添加以下行（允许 www-data 在主题目录执行 git）
www-data ALL=(ALL) NOPASSWD: /usr/bin/git -C /path/to/your/site/wp-content/themes/subo2026 *
```

## 第二步：配置 GitHub Webhook

### 1. 进入仓库设置

1. 访问你的 GitHub 仓库：https://github.com/your-username/your-repo
2. 点击 **Settings**（设置）
3. 左侧菜单选择 **Webhooks**
4. 点击 **Add webhook**（添加 webhook）

### 2. 配置 Webhook

填写以下信息：

| 字段 | 值 |
|------|-----|
| **Payload URL** | `https://example.com/webhook.php` |
| **Content type** | `application/json` |
| **Secret** | 粘贴刚才生成的密钥 |
| **SSL verification** | ✅ Enable SSL verification |
| **Which events would you like to trigger this webhook?** | 选择 "Just the push event" |
| **Active** | ✅ 勾选 |

点击 **Add webhook** 保存。

### 3. 测试 Webhook

GitHub 会自动发送一个测试请求。查看：

1. **GitHub 页面**：
   - 刷新 Webhooks 页面
   - 点击刚创建的 webhook
   - 查看 "Recent Deliveries"
   - 应该看到一个请求，状态码应该是 200

2. **服务器日志**：
   ```bash
   ssh user@server
   tail -f /path/to/your/site/webhook.log
   ```

预期输出：
```
[2026-07-25 10:30:45] 收到 Webhook 请求
[2026-07-25 10:30:45] 签名验证成功
[2026-07-25 10:30:45] 非 push 事件，跳过部署
```

## 第三步：测试自动部署

### 1. 推送测试提交

```bash
# 在本地创建测试文件
echo "# Webhook Test" > WEBHOOK_TEST.md
git add WEBHOOK_TEST.md
git commit -m "test: 测试 webhook 自动部署"
git push origin main
```

### 2. 查看部署日志

```bash
ssh user@server
tail -f /path/to/your/site/webhook.log
```

成功的日志应该类似：
```
[2026-07-25 10:35:20] 收到 Webhook 请求
[2026-07-25 10:35:20] 签名验证成功
[2026-07-25 10:35:20] 目标分支 push 事件，开始部署
[2026-07-25 10:35:20] 提交数量: 1
[2026-07-25 10:35:20] 提交信息: test: 测试 webhook 自动部署
[2026-07-25 10:35:21] 部署成功
[2026-07-25 10:35:21] Git 输出: Already up to date.
```

### 3. 验证文件已更新

```bash
ssh user@server
cd /path/to/your/site/wp-content/themes/subo2026
ls -l WEBHOOK_TEST.md
```

应该能看到刚才创建的测试文件。

## 故障排查

### 问题 1：Webhook 返回 403 Forbidden

**原因**：签名验证失败

**解决方法**：
1. 检查 `webhook.php` 中的 `SECRET_TOKEN` 是否与 GitHub 设置一致
2. 确保没有多余的空格或换行
3. GitHub Webhook 设置中重新输入密钥

### 问题 2：Webhook 返回 500 Internal Server Error

**原因**：PHP 执行错误

**解决方法**：
```bash
# 查看 PHP 错误日志
tail -50 /var/log/php-fpm/error.log
# 或
tail -50 /var/log/apache2/error.log

# 检查文件权限
ls -la /path/to/your/site/webhook.php
ls -la /path/to/your/site/webhook.log
```

### 问题 3：签名验证通过但 git pull 失败

**原因**：权限问题

**解决方法**：
```bash
# 测试 Web 用户能否执行 git
sudo -u www-data git -C /path/to/your/site/wp-content/themes/subo2026 status

# 如果失败，设置正确的权限
cd /path/to/your/site/wp-content/themes/subo2026
chown -R www-data:www-data .
```

### 问题 4：git pull 提示 "Permission denied (publickey)"

**原因**：Web 用户没有 SSH 密钥

**解决方法 1**：使用 HTTPS 方式（推荐公开仓库）

```bash
cd /path/to/your/site/wp-content/themes/subo2026
git remote set-url origin https://github.com/your-username/your-repo.git
```

**解决方法 2**：为 Web 用户配置 SSH 密钥（推荐私有仓库）

```bash
# 切换到 www-data 用户
sudo -u www-data bash

# 生成 SSH 密钥
ssh-keygen -t ed25519 -C "webhook@example.com" -f ~/.ssh/id_ed25519 -N ""

# 查看公钥
cat ~/.ssh/id_ed25519.pub

# 将公钥添加到 GitHub：
# Settings → SSH and GPG keys → New SSH key
```

### 问题 5：部署成功但看不到更新

**原因**：缓存

**解决方法**：
```bash
# 清除 WordPress 缓存
wp cache flush

# 清除 Opcache
# 创建临时文件：/path/to/your/site/opcache-reset.php
<?php opcache_reset(); echo "Opcache cleared"; ?>

# 访问该文件后删除
```

## 安全建议

### 1. 限制访问 IP（可选）

在 Nginx 配置中添加：

```nginx
location = /webhook.php {
    # GitHub Webhook IP 范围
    allow 140.82.112.0/20;
    allow 143.55.64.0/20;
    allow 185.199.108.0/22;
    allow 192.30.252.0/22;
    deny all;

    fastcgi_pass unix:/var/run/php/php-fpm.sock;
    include fastcgi_params;
}
```

### 2. 使用强密钥

- 至少 32 字节的随机字符串
- 定期更换（建议每 6 个月）
- 不要在代码中硬编码，使用环境变量

### 3. 监控日志

定期检查 webhook.log，发现异常及时处理：

```bash
# 添加到 crontab
0 9 * * * grep "签名验证失败" /path/to/your/site/webhook.log && echo "发现可疑请求" | mail -s "Webhook 安全警告" admin@example.com
```

### 4. 日志轮转

防止日志文件过大：

```bash
# 创建 logrotate 配置
sudo nano /etc/logrotate.d/webhook

# 添加内容
/path/to/your/site/webhook.log {
    daily
    rotate 7
    compress
    missingok
    notifempty
    create 666 www-data www-data
}
```

## 高级配置

### 多分支部署

如果需要部署多个分支到不同目录：

```php
// 在 webhook.php 中修改
$branch_map = [
    'refs/heads/main' => '/path/to/your/site/wp-content/themes/subo2026',
    'refs/heads/dev' => '/www/sites/example.com/dev/wp-content/themes/subo2026',
];

$ref = $data['ref'] ?? '';
if (!isset($branch_map[$ref])) {
    die(json_encode(['message' => 'Branch not configured']));
}

define('REPO_PATH', $branch_map[$ref]);
```

### 部署后钩子

在 git pull 成功后执行自定义命令：

```php
// 在 execute_git_pull() 函数中添加
if ($result['success']) {
    // 清除缓存
    exec('wp cache flush --path=/path/to/your/site', $output);
    
    // 重启 PHP-FPM（需要 sudo 权限）
    exec('sudo systemctl reload php-fpm', $output);
    
    // 发送通知
    file_get_contents('https://api.example.com/notify?message=deployed');
}
```

## 备份方案

如果无法使用 Webhook，可以使用 cron 定时拉取：

```bash
# 编辑 crontab
crontab -e

# 每 5 分钟检查一次更新
*/5 * * * * cd /path/to/your/site/wp-content/themes/subo2026 && git pull origin main >> /var/log/auto-deploy.log 2>&1
```

## 总结

设置完成后，你的工作流程变为：

```
本地修改 → git commit → git push → 自动部署 ✅
```

不再需要手动 SSH 到服务器执行 git pull！

---

**创建时间**: 2026-07-25  
**相关文件**: deploy/webhook.php
