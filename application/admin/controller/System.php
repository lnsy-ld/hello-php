<?php

namespace app\admin\controller;

class System extends Base
{

    public function __construct()
    {
        parent::__construct();
        $this->assign('module', '系统管理');
    }

    public function clearCache()
    {
        $this->assign('action', '缓存清理');
        $this->assign('page', '选择');
        $list = [
            [
                'type' => 'html',
                'name' => '页面缓存',
                'path' => HTML_CACHE_PATH,
                'size' => $this->dirSize(HTML_CACHE_PATH)
            ],
            [
                'type' => 'cache',
                'name' => '网站缓存',
                'path' => CACHE_PATH,
                'size' => $this->dirSize(CACHE_PATH)
            ],
            [
                'type' => 'temp',
                'name' => '模板缓存',
                'path' => TEMP_PATH,
                'size' => $this->dirSize(TEMP_PATH)
            ],
        ];
        if (request()->method() == 'GET') {
            $this->assign('list', $list);
            $this->myAssign();
            return $this->myFetch();
        } else {
            $param = request()->param();
            $clearType = $param['cache_type']? : '';
            foreach ($list as $value) {
                if (in_array($value['type'], $clearType)) {
                    $this->deleteDir($value['path']);
                }
            }
            $this->redirect('clearCache');
        }
    }

    public function deleteDir($dir)
    {
        //先删除目录下的文件： 
        if (is_dir($dir)) {
            $dh = opendir($dir);
            while ($file = readdir($dh)) {
                if ($file != "." && $file != "..") {
                    $fullpath = $dir."/".$file;
                    if (!is_dir($fullpath)) {
                        unlink($fullpath);
                    } else {
                        $this->deleteDir($fullpath);
                    }
                }
            }
            closedir($dh);
            //删除当前文件夹：  
            if (rmdir($dir)) {
                return true;
            } else {
                return false;
            }
        }  elseif(is_file($dir)){
            unlink($dir);
        }else{
            return false;
        }
    }

    public function dirSize($dir)
    {
        if (!is_dir($dir)) {
            return 0;
        }
        $dir_list = opendir($dir);
        $dir_size = 0;
        while (false !== ($folder_or_file = readdir($dir_list))) {
            if ($folder_or_file != "." && $folder_or_file != "..") {
                if (is_dir("$dir/$folder_or_file")) {
                    $dir_size += self::dirSize("$dir/$folder_or_file");
                } else {
                    $dir_size += filesize("$dir/$folder_or_file");
                }
            }
        }
        closedir($dir_list);
        return $dir_size;
    }

    public function openRegister()
    {
        $this->assign('action', '开放注册');
        $this->assign('page', '设置');
        $this->myAssign();
        return $this->myFetch();
    }

    public function database()
    {
        $this->assign('action', '数据库设置');
        $this->assign('page', '设置');
        $this->myAssign();
        return $this->myFetch();
    }
}
