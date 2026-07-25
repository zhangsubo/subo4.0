# 快速开始：GitHub Webhook 自动部署

## 5 分钟快速配置

### 1️⃣ 生成密钥

```bash
openssl rand -hex 32
```

保存输出的密钥（例如：`a3f9d8e7c6b5a4f3...`）

### 2️⃣ 上传并配置脚本

```bash
# SSH 到服务器
ssh user@server

# 上传脚本
cd /path/to/your/site
wget https://raw.githubusercontent.com/your-username/your-repo/main/deploy/webhook.php

# 或者通过 Git
cd /path/to/your/site/wp-content/themes/subo2026
git pull
cp deploy/webhook.php /path/to/your/site/

# 编辑配置
nano /path/to/your/site/webhook.php
```

修改这三行：
```php
define('SECRET_TOKEN', '粘贴你的密钥');
define('BRANCH', 'main');
define('REPO_PATH', '/path/to/your/site/wp-content/themes/subo2026');
```

### 3️⃣ 设置权限

```bash
chmod 644 /path/to/your/site/webhook.php
touch /path/to/your/site/webhook.log
chmod 666 /path/to/your/site/webhook.log
chown www-data:www-data /path/to/your/site/webhook.*

# 确保可以执行 git
cd /path/to/your/site/wp-content/themes/subo2026
chown -R www-data:www-data .
```

### 4️⃣ 配置 GitHub

1. 访问 https://github.com/your-username/your-repo/settings/hooks
2. 点击 **Add webhook**
3. 填写：
   - **Payload URL**: `https://example.com/webhook.php`
   - **Content type**: `application/json`
   - **Secret**: 粘贴你的密钥
   - **Events**: Just the push event
4. 点击 **Add webhook**

### 5️⃣ 测试

```bash
# 本地推送测试
git commit --allow-empty -m "test: webhook"
git push

# 查看服务器日志
ssh user@server
tail -f /path/to/your/site/webhook.log
```

看到 "部署成功" 就完成了！🎉

---

**完整文档**: docs/WEBHOOK_DEPLOY.md
