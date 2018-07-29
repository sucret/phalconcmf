# PhalconCMS

## 使用Phalcon框架简单实现的一个后台管理系统 
 
### 功能实现

- 管理员账号管理
- RBAC权限管理
- 登录登出
- ...

### 开发环境
nginx、php-fpm、mysql、Phalcon 3.4.0

### 数据库相关
数据库文件 /public/database.sql

### 账号相关
初始管理员账号：admin  
初始管理员密码：admin

### nginx配置

```
server{
    listen 80;

    server_name phalconcms.cc ;
    index index.php;
    access_log  /mnt/docker/nginx/log/phalconcms.cc.log;
    root  /var/web/phalconcmf/public;
    try_files $uri $uri/ @rewrite;

    location @rewrite {
        rewrite ^/(.*)$ /index.php?_url=/$1;
    }
    
    location ~ .*\.(gif|jpg|jpeg|png|bmp|swf)$
    {
        expires      30d;
    }

    location ~ .*\.(js|css)?$
    {
        expires      12h;
    }

    location ~ /\.
    {
        deny all;
    }

    location ~ \.php$ {
        root           /var/web/phalconcmf/public;
        fastcgi_pass   phpfpm:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }
}

```

### 目录结构
```
├── app
│   ├── admin
│   │   ├── controller
│   │   └── view
│   │       ├── admin
│   │       └── menu
│   ├── api
│   └── common
│       ├── config
│       ├── model
│       └── module
├── public
│   └── static
│       ├── css
│       └── js
└── vendor

```


