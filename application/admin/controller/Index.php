<?php

namespace app\admin\controller;

class Index extends Base
{

    public function index()
    {
        $this->assign('action', '欢迎登录');
        $this->assign('page', '');
        $this->myAssign();
        return $this->myFetch();
    }
}
