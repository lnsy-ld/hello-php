<?php

namespace app\admin\controller;

use think\Db;

class Log extends Base
{

    public function action()
    {
        $list = Db::name('action_log')->order('id desc')->paginate(10)->each(function($item, $key) {
            return $item;
        });
        $this->assign('list_count', count($list));
        $this->assign('list', $list);
        $this->assign('action', '文章管理');
        $this->assign('page', '已发布文章列表');
        $this->myAssign();
        return $this->myFetch();
    }

    public function login()
    {
        $list = Db::name('login_log')->order('id desc')->paginate(10)->each(function($item, $key) {
            $item['login_addr'] = long2ip($item['login_addr']);
            $item['login_result'] = $item['login_result']?'登录成功':'登录失败';
            return $item;
        });
        $this->assign('list_count', count($list));
        $this->assign('list', $list);
        $this->assign('action', '文章管理');
        $this->assign('page', '已发布文章列表');
        $this->myAssign();
        return $this->myFetch();
    }
}
