<?php

namespace app\index\controller;

use think\Controller;
use think\Db;

class Base extends Controller
{

    public function __construct()
    {
        header("Content-type:text/html; charset='utf-8'");
        $this->getOb();
        parent::__construct();
        $this->assignMenu();
        $this->statisticsAccess();
        $remote_addr = $_SERVER['REMOTE_ADDR'];
        cookie('SESSIONID', md5($remote_addr.'hello-php'));
    }

    private function statisticsAccess()
    {
        if (isset($_COOKIE['SESSIONID'])) {
            $session_id = $_COOKIE['SESSIONID'];
            $session_prefix = 'access_user_'.$session_id;
            if (!$this->redis->get($session_prefix)) {
                $this->redis->incr('site_access_count');
            }
            $this->redis->set($session_prefix, true, ACCESS_VALIDITY_TIME);
        }
    }

    private function assignMenu()
    {
        $menus = $this->getMenuList();
        $this->assign('host', $_SERVER['SERVER_NAME'] ?: $_SERVER['HTTP_HOST']);
        $this->assign('menus', $menus);
    }

    public function getMenuList($parent_id = 0)
    {
        $list = Db::name('menus')->where(['parent_id' => $parent_id])->order('order_weight desc')->select();
        foreach ($list as $key => $val) {
            $list[$key]['status_msg'] = $val['status'] ? '开启' : '关闭';
            $list[$key]['target_msg'] = $val['target_type'] == '_blank' ? '新窗口' : '当前窗口';
            $haveChildren = Db::name('menus')->where(['parent_id' => $val['menu_id']])->find();
            if (!empty($haveChildren)) {
                $list[$key]['childrens'] = $this->getMenuList($val['menu_id']);
            } else {
                $list[$key]['childrens'] = [];
            }
        }

        return $list;
    }

    private function getOb()
    {
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
            }else{
               unlink($fullName);
            }
        }
    }
}
