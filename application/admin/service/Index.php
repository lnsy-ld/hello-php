<?php

namespace app\index\controller;


class Index extends Base
{

    public function index()
    {
        $info['str'] = 'lidong';
        $this->assign('str',$info);
        return $this->fetch('index');
    }
}
