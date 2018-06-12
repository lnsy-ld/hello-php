<?php

namespace app\behavior;

class ReadHtmlCache
{

    public function run()
    {
        return FALSE;
        $module = request()->module();
        if (!in_array($module, config('html_cache_module')) || !get_cfg('html_cache')) {
            return FALSE;
        }
        $url = request()->url();
        $cachePath = HTML_CACHE_PATH;
        $fileName = md5($url).'.'.VIEW_SUFFIX;
        $fullName = $cachePath.DS.$fileName;
        if (request()->isGet()) {
            //如果缓存文件存在，并且没有过期，就把它读出来。
            if (file_exists($fullName) && time() - filemtime($fullName) < get_cfg('html_cache_expire', 3600)) {
                $file = fopen($fullName, 'r');
                $content = fread($file, filesize($fullName));
                echo $content;
                exit;
            } elseif (!file_exists($fullName)) {
                if (!file_exists($cachePath)) {
                    mkdir($cachePath, 0777);
                    chmod($cachePath, 0777);
                }
            }
        }
        return false;
    }
}
