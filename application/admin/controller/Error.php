<?php

namespace app\admin\controller;

class Error extends Base
{

    public function index()
    {
        $this->redirect('login/index');
    }
}
