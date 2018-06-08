<?php

namespace app\behavior;

class WriteHtmlCache
{

    //回调函数，当程序结束时自动调用此函数
    public function run(&$contents)
    {
        $module = request()->module();
        if (!in_array($module, config('html_cache_module')) || !get_cfg('html_cache')) {
            return FALSE;
        }
        $url = request()->url();
        $cachePath = HTML_CACHE_PATH;
        $fileName = md5($url).'.'.VIEW_SUFFIX;
        $fullName = $cachePath.DS.$fileName;
        $fp = fopen($fullName, 'w+');
        fwrite($fp, $contents);
        fclose($fp);
        chmod($fullName, 0777);
        //生成新缓存的同时，自动删除所有的老缓存。以节约空间。
        return TRUE;
    }
}
