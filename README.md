## 安装

（1）下载
需要使用 composer 工具安装，安装命令如下

```shell
composer require zhchenxin/laravel-upload
```

（2）修改配置文件

打开配置文件 `config/app.php` ，然后增加以下行代码：

```php
'providers' => array(

    [...]

    'Zhchenxin\Upload\ServiceProvider'
),
```

## 文件上传

### 上传接口

```
curl -X POST \
  http://localhost/upload \
  -H 'content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW' \
  -F file=@efqomc0rcmu.jpg
```

如果请求成功，则返回：

```json
{
    "code": 0,
    "data": {
        "filename": "/c/20190122/0737/ZyIrDimgiitp1V1E.jpg",
        "file_url": "http://localhost/c/20190122/0737/ZyIrDimgiitp1V1E.jpg"
    }
}
```

其中，使用 `file_url` 字段，可以预览图片。

### 图片压缩

如果上传的是 'png', 'jpg', 'jpeg', 'gif'，则这些图片可以进行压缩显示

```
压缩成长：200 宽：100 的图片
http://localhost/c/20190122/0737/ZyIrDimgiitp1V1E_200_100.jpg
```