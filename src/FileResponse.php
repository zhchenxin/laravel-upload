<?php

namespace Zhchenxin\Upload;


use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileResponse extends BinaryFileResponse
{
    public function setFile($file, $contentDisposition = null, $autoEtag = false, $autoLastModified = true)
    {
        parent::setFile($file, $contentDisposition, $autoEtag, $autoLastModified);

        if ($autoLastModified && $this->_checkIfModifiedSince()) {
            abort(304);
        }

        if ($autoEtag && $this->_checkETag()) {
            abort(304);
        }

        return $this;
    }

    /**
     * 检查是否可以使用浏览器缓存
     * @return bool
     */
    private function _checkIfModifiedSince()
    {
        if (empty($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            return false;
        }

        if (!$this->headers->has('Last-Modified')) {
            return false;
        }

        $lastModified = strtotime($this->headers->get('Last-Modified'));

        return $_SERVER['HTTP_IF_MODIFIED_SINCE'] <= $lastModified;
    }

    /**
     * 检测 ETag
     * @return bool
     */
    private function _checkETag()
    {
        if (empty($_SERVER['HTTP_IF_NONE_MATCH'])) {
            return false;
        }

        if (!$this->headers->has('ETag')) {
            return false;
        }

        return $_SERVER['HTTP_IF_NONE_MATCH'] == $this->headers->get('ETag');
    }
}