## 基于Laravel5.3开发的考勤系统

本项目为毕业设计作品

## 环境需求
. Linux系统  
. Nginx或Apache  
. MySQL3.7  
. PHP7.0  
. redis  
. composer  
. node环境  

## 安装
安装Laravel依赖
```
composer update
```
安装gulp命令行工具
```
npm install gulp -g
```
安装npm依赖包
```
npm install
```

## 配置
将`.env.example`改名为`.env`  
修改`APP_URL`的值为`http://www.kq.cc`  
添加`TIMEZONE=RPC`  
修改`SESSION_DRIVER`的值为`redis`  
添加`SESSION_DOMAIN=.kq.cc`  
`Mail`配置进行自行修改，以下为参考配置  
```
MAIL_DRIVER=smtp
MAIL_HOST=smtp.qq.com
MAIL_PORT=email_port
MAIL_USERNAME=email_username
MAIL_PASSWORD=email_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_NAME=教学考勤管理
MAIL_FROM_ADDRESS=email_email
```
项目根目录下运行如下指令，修改`APP_KEY`
```
php artisan key:generate
```

项目根目录下运行如下指令，压缩html，css,js
```
gulp compress
```

## 开始使用  
登录依赖login项目，安装login项目请移步查看