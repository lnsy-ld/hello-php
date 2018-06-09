<?php

namespace app\admin\controller\site;

use app\admin\controller\Base;

class Set extends Base
{

    public function __construct()
    {
        parent::__construct();
        $this->assign('module', '网站内容管理');
    }

    public function site_info()
    {
        $this->assign('action', '网站基本信息');
        $this->assign('page', '设置');
        $this->myAssign();
        return $this->myFetch();
    }

    public function save_config()
    {
        $param = $this->request->param();

        foreach ($param as $key => $value) {
            set_cfg($key, $value);
        }
        $site_logo = request()->file('site_logo');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($site_logo) {
            $uploads_path = ROOT_PATH.'public'.DS.'uploads';
            $info = $site_logo->move($uploads_path);
            if ($info) {
                set_cfg('site_logo', DS.'public'.DS.'uploads'.DS.$info->getSaveName());
            } else {
                // 上传失败获取错误信息
                echo $site_logo->getError();
                $this->assign('file_error_msg', $site_logo->getError());
            }
        }
        $avatar_image = request()->file('avatar_image');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($avatar_image) {
            $uploads_path = ROOT_PATH.'public'.DS.'uploads';
            $info = $avatar_image->move($uploads_path);
            if ($info) {
                set_cfg('avatar_image', DS.'public'.DS.'uploads'.DS.$info->getSaveName());
            } else {
                // 上传失败获取错误信息
                echo $avatar_image->getError();
                $this->assign('file_error_msg', $avatar_image->getError());
            }
        }
        return $this->redirect('site_info');
    }
}
