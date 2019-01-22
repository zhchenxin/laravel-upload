## 安装

（1）下载
需要使用 composer 工具安装，安装命令如下

```shell
composer require zhchenxin/upload
```

（2）修改配置文件

打开配置文件 `config/app.php` ，然后增加以下行代码：

```php
'providers' => array(

    [...]

    'Zhchenxin\Upload\ServiceProvider'
),
```