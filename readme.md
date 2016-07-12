
## 项目概述

* 产品名称：PHPHub5
* 项目代码：PHPHub5
* 官方地址：https://phphub.org/

phphub.org 升级版

## 运行环境

- Nginx 1.8+
- PHP 5.6+
- Mysql 5.7+
- Redis 3.0+
- Memcached 1.4+

## 开发环境部署/安装

本项目代码使用 PHP 框架 [Laravel 5.1](http://laravel-china.org/docs/5.1/) 开发，本地开发环境使用 [Laravel Homestead](http://laravel-china.org/docs/5.1/homestead)。

下文将在假定读者已经安装好了 Homestead 的情况下进行说明。如果您还未安装 Homestead，可以参照 [Homestead 安装与设置](http://laravel-china.org/docs/5.1/homestead#installation-and-setup) 进行安装配置。

### 基础安装

#### 1. 克隆源代码

克隆源代码到本地：

    > git clone git@github.com:summerblue/phphub5.git

#### 2. 配置本地的 Homestead 环境

1). 运行以下命令编辑 Homestead.yaml 文件：

```shell
homestead edit
```

2). 加入对应修改，如下所示：

```
folders:
    - map: ~/my-path/phphub5/ # 你本地的项目目录地址
      to: /home/vagrant/phphub5
sites:
    - map: phphub5.app
      to: /home/vagrant/phphub5/public

databases:
    - phphub5
```

3). 应用修改

修改完成后保存，然后执行以下命令应用配置信息修改：

```shell
homestead provision
```

> 注意：有时候你需要重启才能看到应用。运行 `homestead halt` 然后是 `homestead up` 进行重启。

#### 3. 安装扩展包依赖

    > composer install

#### 4. 生成配置文件

    > cp .env.example .env

#### 5. 使用安装命令

虚拟机里面：

```shell
php artisan est:install
```

> 更多信息，请查阅 ESTInstallCommand

#### 6. 配置 hosts 文件

主机里:

    echo "192.168.10.10   phphub5.app" | sudo tee -a /etc/hosts

### 前端框架安装

1). 安装 node.js

直接去官网 [https://nodejs.org/en/](https://nodejs.org/en/) 下载安装最新版本。

2). 安装 Gulp

```shell
npm install --global gulp
```

3). 安装 Laravel Elixir

```shell
npm install
```

4). 直接 Gulp 编译前端内容

```shell
gulp
```

5). 监控修改并自动编译

```shell
gulp watch
```

### 链接入口

* 首页地址：http://phphub5.app/
* 管理后台：http://phphub5.app/admin

在开发环境下，直接访问后台地址即可登录 1 号用户

至此, 安装完成
