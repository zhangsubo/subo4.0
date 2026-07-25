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
cd /www/sites/zhangsubo.cn/index
wget https://raw.githubusercontent.com/zhangsubo/subo4.0/main/deploy/webhook.php

# 或者通过 Git
cd /www/sites/zhangsubo.cn/index/wp-content/themes/subo2026
git pull
cp deploy/webhook.php /www/sites/zhangsubo.cn/index/

# 编辑配置
nano /www/sites/zhangsubo.cn/index/webhook.php
```

修改这三行：
```php
define('SECRET_TOKEN', '粘贴你的密钥');
define('BRANCH', 'main');
define('REPO_PATH', '/www/sites/zhangsubo.cn/index/wp-content/themes/subo2026');
```

### 3️⃣ 设置权限

```bash
chmod 644 /www/sites/zhangsubo.cn/index/webhook.php
touch /www/sites/zhangsubo.cn/index/webhook.log
chmod 666 /www/sites/zhangsubo.cn/index/webhook.log
chown www-data:www-data /www/sites/zhangsubo.cn/index/webhook.*

# 确保可以执行 git
cd /www/sites/zhangsubo.cn/index/wp-content/themes/subo2026
chown -R www-data:www-data .
```

### 4️⃣ 配置 GitHub

1. 访问 https://github.com/zhangsubo/subo4.0/settings/hooks
2. 点击 **Add webhook**
3. 填写：
   - **Payload URL**: `https://zhangsubo.cn/webhook.php`
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
tail -f /www/sites/zhangsubo.cn/index/webhook.log
```

看到 "部署成功" 就完成了！🎉

---

**完整文档**: docs/WEBHOOK_DEPLOY.md
