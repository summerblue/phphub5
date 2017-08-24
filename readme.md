<p align="center">
  <br>
  <b>创造不息，交付不止</b>
  <br>
  <a href="https://www.yousails.com">
    <img src="https://yousails.com/banners/brand.png" width=350>
  </a>
  
  <br>
  <a href="https://laravel-china.org/topics/3383/laravel-the-first-chinese-new-book-laravel-tutorial">
    <img src="http://ww1.sinaimg.cn/large/6d86d850gw1fao8va0fv0j208y0aw74v.jpg" width=200>
  </a>
</p>

---

![macbook](https://cloud.githubusercontent.com/assets/324764/18414545/d875e012-77ff-11e6-9249-0b56a6460cb8.png)

## 项目概述

* 产品名称：PHPHub5
* 项目代码：PHPHub5
* 官方地址：https://laravel-china.org/

[PHPHub](https://github.com/summerblue/phphub) Laravel 5.1 版本。

## 运行环境

- Nginx 1.8+
- PHP 5.6+
- Mysql 5.7+
- Redis 3.0+
- Memcached 1.4+

## 开发环境部署/安装

本项目代码使用 PHP 框架 [Laravel 5.1](https://doc.laravel-china.org/docs/5.1/) 开发，本地开发环境使用 [Laravel Homestead](https://doc.laravel-china.org/docs/5.1/homestead)。

下文将在假定读者已经安装好了 Homestead 的情况下进行说明。如果您还未安装 Homestead，可以参照 [Homestead 安装与设置](https://doc.laravel-china.org/docs/5.1/homestead#installation-and-setup) 进行安装配置。

### 基础安装

#### 1. 克隆源代码

克隆源代码到本地：

    > git clone https://github.com/summerblue/phphub5.git

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

### 前端工具集安装

> 代码里自带编译后的前端代码，如果你不想开发前端样式的话，你是不需要配置前端工具集的，可直接跳过直达 `链接入口` 部分。

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

> 请修改 `.env` 文件为 `APP_ENV=local` 和 `APP_DEBUG=true` 。

* 首页地址：http://phphub5.app/
* 管理后台：http://phphub5.app/admin

在开发环境下，直接访问后台地址即可登录 1 号用户。

至此, 安装完成。

## 扩展包描述

| 扩展包 | 一句话描述 | 在本项目中的使用案例 |  
| --- | --- | --- |   
|[infyomlabs/laravel-generator](https://packagist.org/packages/infyomlabs/laravel-generator)| Laravel 代码生成器 | 开发时的 Migration、Model、Controller 都使用此扩展包生成。 |  
| [orangehill/iseed](https://github.com/orangehill/iseed) | 将数据表里的数据以 seed 的方式导出 | BannersTableSeeder, LinksTableSeeder, CategoriesTableSeeder 和 TipsTableSeeder 使用此扩展包生成。 |
| [barryvdh/laravel-debugbar](https://github.com/barryvdh/laravel-debugbar) | 调试工具栏 | 开发时必备调试工具。 |
|[rap2hpoutre/laravel-logviewer](https://github.com/rap2hpoutre/laravel-log-viewer)| Log 查看工具 | 生产环境下，使用此扩展包快速查看 Log，已做权限控制。 |
| [laracasts/presenter](https://github.com/laracasts/Presenter) | Presenter 机制 | 以下 Model: User、Topic、Notification 都使用到了 Presenter。 |
|[league/html-to-markdown](https://github.com/thephpleague/html-to-markdown)| 将 HTML 转换成 Markdown| 用户发帖、回复帖子时使用了此扩展包。 |
|[erusev/parsedown](https://github.com/erusev/parsedown)| 将 Markdown 转换成 HTML| 用户发帖、回复帖子时使用了此扩展包。 |
| [laravel/socialite](https://github.com/laravel/socialite) | 官方社会化登录组件 | GitHub 登录逻辑使用了此扩展包。 |
|[NauxLiu/auto-correct](https://github.com/NauxLiu/auto-correct)| 自动给中英文之间加入合理的空格，纠正专用名词大小写| 用户发帖时用此扩展包过滤标题。 |
| [Intervention/image](https://github.com/Intervention/image) | 图片处理功能库 | 用发帖和回复帖子时，图片上传的逻辑使用了此扩展包。 |
| [zizaco/entrust](https://github.com/Zizaco/entrust.git) | 用户组权限系统 | 整站的权限系统基于此扩展包开发。 |
| [VentureCraft/revisionable](https://github.com/VentureCraft/revisionable) | 记录 Model 的变更日志 | 以下 Model: User, Topic, Reply, Category, Banner 都用此扩展包记录删除日志。|
| [mews/purifier](https://github.com/mewebstudio/Purifier) | HTML 白名单过滤器 | 用户发帖、回复时防止 XSS 过滤。 |
|[oumen/sitemap](https://github.com/RoumenDamianoff/laravel-sitemap)| Sitemap 生成工具| 本项目的 sitemap 使用此扩展包生成。 |
|[spatie/laravel-backup](https://github.com/spatie/laravel-backup)| 数据库备份解决方案 | 本项目的数据库备份使用此扩展包完成。 |
|[summerblue/administrator](https://github.com/summerblue/administrator)| 管理后台解决方案| 本项目的后台使用此扩展包开发。 |
|[laracasts/flash](https://packagist.org/packages/laracasts/flash)| 简单的 flash messages | 用户登录成功、发帖成功后的提示使用此扩展包开发 |


## 自定义 Artisan 命令列表

| 命令 | 说明 |
| --- | --- |
| est:install | 安装命令，仅支持开发环境下运行，在初次安装才有必要运行。|
| est:reinstall | 重装命令，仅支持开发环境下运行，调用此命令会重置数据库、重置用户身份。|

## 计划任务

此项目的计划任务都以 Laravel 的 [任务调度](https://doc.laravel-china.org/docs/5.1/scheduling) 方式执行。

| 命令 | 说明 | 调用 |
| --- | --- | --- |
| `backup:run --only-db` | 数据库备份，每 4 小时运行一次，属于 [spatie/laravel-backup](https://github.com/spatie/laravel-backup) 的逻辑 | php artisan backup:run --only-db|
| `backup:clean` | 清理过期的数据库备份，每日 1:20 运行，属于 [spatie/laravel-backup](https://github.com/spatie/laravel-backup) 的逻辑 | php artisan backup:clean |


## 代码生成器日志

本项目使用 [infyomlabs/laravel-generator](https://packagist.org/packages/infyomlabs/laravel-generator) 快速构建项目， 记录这些日志目的为了方便后续开发可以借鉴。

```shell

php artisan make:scaffold Appends --schema="content:text,topic_id:integer:unsigned:default(0):index"

php artisan make:scaffold Attentions --schema="topic_id:integer:unsigned:default(0):index,user_id:integer:unsigned:default(0):index"

php artisan make:scaffold Links --schema="title:string:index,link:string:index,cover:text:nullable"

php artisan make:scaffold Replies --schema="topic_id:integer:unsigned:default(0):index,user_id:integer:unsigned:default(0):index,is_block:tinyInteger:unsigned:default(0):index,vote_count:integer:unsigned:default(0):index,body:text,body_original:text:nullable"

php artisan make:scaffold SiteStatuses --schema="day:string:index,register_count:integer:unsigned:default(0),topic_count:tinyInteger:unsigned:default(0),reply_count:integer:unsigned:default(0),image_count:integer:unsigned:default(0)"

php artisan make:scaffold Tips --schema="body:text:nullable"

php artisan make:scaffold Topics --schema="title:string:index,body:text,user_id:tinyInteger:unsigned:default(0),category_id:integer:unsigned:default(0),reply_count:integer:unsigned:default(0),view_count:integer:unsigned:default(0),vote_count:integer:unsigned:default(0),last_reply_user_id:integer:unsigned:default(0),order:integer:unsigned:default(0),is_excellent:tinyInteger:unsigned:default(0),is_wiki:tinyInteger:unsigned:default(0),is_blocked:tinyInteger:unsigned:default(0),body_original:text:nullable,excerpt:text:nullable"

php artisan make:scaffold Topics --schema="user_id:integer:unsigned:default(0),votable_id:integer:unsigned:default(0),votable_type:string:index,is:string:index"

php artisan make:scaffold Users --schema="github_id:integer:unsigned:default(0):index,github_url:string:index,email:string:index:index,name:string:index:index"

php artisan make:scaffold Votes --schema="user_id:integer:unsigned:default(0),votable_id:integer:unsigned:default(0),votable_type:string:index,is:string:index"

php artisan make:scaffold Banners --schema="position:string:index,order:integer:unsigned:default(0):index,image_url:string,title:string:index,description:text:nullable"

php artisan make:scaffold NotificationMailLogs --schema="from_user_id:integer:unsigned:default(0):index,user_id:integer:unsigned:default(0):index,type:string:index,body:text:nullable"
```

## License

> 使用 PHPHub5 构建，或者基于 PHPHub5 源代码修改的站点 **必须** 在页脚加上 `Powered by PHPHub` 字样，并且必须链接到 `https://laravel-china.org` 上。**必须** 在页面的每一个标题上加上 `Powered by PHPHub` 字样。

在遵守以上规则的情况下，你可以享受等同于 MIT 协议的授权。

或者你可以联系 `aufree@yousails.com` 购买商业授权，商业授权允许移除页脚和标题的 `Powered by PHPHub` 字样。
