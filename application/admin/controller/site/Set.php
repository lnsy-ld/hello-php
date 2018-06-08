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
        $file = request()->file('site_logo');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $uploads_path = ROOT_PATH.'public'.DS.'uploads';
            $info = $file->move($uploads_path);
            if ($info) {
                set_cfg('site_logo', DS.'public'.DS.'uploads'.DS.$info->getSaveName());
            } else {
                // 上传失败获取错误信息
                echo $file->getError();
                $this->assign('file_error_msg', $file->getError());
            }
        }
        return $this->redirect('site_info');
    }
}
