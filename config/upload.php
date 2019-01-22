<?php

return [
    /**
     * 最大上传文件大小，单位 kb
     */
    'max_size' => 5000,

    /**
     * 后缀白名单，为空表示无限制
     */
    'extension' => [],

    /**
     * 上传文件保存的路径，最好保持默认
     */
    'save_path' => public_path('c'),

    'route_prefix' => '',
    'route_domain' => null,
];