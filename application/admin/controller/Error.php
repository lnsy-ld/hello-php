<?php

namespace app\admin\controller;

class Error extends Base
{

    public function index()
    {
        exit(json_encode(2, JSON_UNESCAPED_UNICODE));
        $this->redirect('login/index');
    }
}
