<?php

namespace Zhchenxin\Upload\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Intervention\Image\Facades\Image;
use Zhchenxin\Upload\FileResponse;

class UploadController
{
    public function upload(Request $request)
    {
        $maxSize = Config::get('upload.max_size');
        $extension = Config::get('upload.extension');
        $savePath = Config::get('upload.save_path');

        $file = $request->file('file');
        if (!$file || !$file->isValid()) {
            return response()->json(['code' => 2, 'message' => 'File not exist'])->setStatusCode(400);
        }

        if ($file->getSize() > $maxSize * 1024) {
            return response()->json(['code' => 3, 'message' => 'file size too large'])->setStatusCode(400);
        }

        if (!empty($extension)) {
            if (in_array(strtolower($file->getClientOriginalExtension()), $extension)) {
                return response()->json(['code' => 4, 'message' => 'file extension not allow'])->setStatusCode(400);
            }
        }

        // 移动上传的文件
        $filename = sprintf('/%s/%s/%s.%s', date('Ymd'), date('Hi'), str_random(), $file->getClientOriginalExtension());
        $file->move(dirname($savePath . $filename), basename($filename));

        // 返回值
        return response()->json([
            'code' => 0,
            'data' => [
                'filename' => '/c' . $filename,
                'file_url' => Config::get('app.url') . '/c' . $filename,
            ],
        ]);
    }

    public function show($day, $time, $file)
    {
        $savePath = Config::get('upload.save_path');

        $filename = $savePath . "/$day/$time/$file";

        if (file_exists($filename)) {
            return $this->_showFile($filename);
        }

        // 只有图片才能压缩
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        if (in_array(strtolower($extension), ['png', 'jpg', 'jpeg', 'gif'])) {
            return $this->_resizeImage($day, $time, $file, $extension);
        }

        abort(404);
    }

    /**
     * @param $filename
     * @return FileResponse
     */
    private function _showFile($filename)
    {
        FileResponse::trustXSendfileTypeHeader();

        $response = new FileResponse($filename, 200, [], true, false, true,false);
        $response->headers->set('Cache-Control','public,max-age=2592000');
        return $response;
    }

    private function _resizeImage($day, $time, $file, $extension)
    {
        $savePath = Config::get('upload.save_path');

        // 获取原始的文件路径
        $rowFile = $width = $height = 0;
        if (strpos($file, '_')) {
            $tmp = explode('_', $file);
            $rowFile = $tmp[0] . ".$extension";
            $width = intval($tmp[1]);
            $height = intval($tmp[2]);
        }

        $savedFilename = $savePath . "/$day/$time/$file";
        $rowFilename = $savePath . "/$day/$time/$rowFile";

        // 创建压缩文件
        if (!file_exists($rowFilename)) {
            abort(404);
        }
        Image::make($rowFilename)->resize($width,$height)->save($savedFilename);
        return $this->_showFile($savedFilename);
    }
}