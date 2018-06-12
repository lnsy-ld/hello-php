<?php

namespace app\index\controller;
use think\Db;

class Page extends Base
{

    public function index()
    {
        $param = request()->param();
        $id = $param['id'];
        $info = Db::name('pages')->order('update_time desc')->find($id);
        $menus = $this->getMenuList();
        $this->assign('menus',$menus);
        $this->assign('info', $info);
        $this->assign('page_keywords',$info['keywords']);
        $this->assign('title', '首页');
        return $this->fetch();
    }
    
    
}
