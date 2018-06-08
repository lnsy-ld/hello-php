<?php

namespace app\admin\controller;

use think\Cache;
use app\common\model\User;
use think\captcha\Captcha;

class Login extends Base
{

    public function index()
    {
        return $this->fetch();
    }

    public function login()
    {
        $param = $this->request->param();

//        if (!captcha_check($param['code'])) {
////            $this->error('验证码错误!', 'index', 1);
//        }
        $name = $param['username'];
        $password = $param['userpwd'];
        $model = new User();
        $pwd = md5($password.PWD_SIGN);
//        echo $pwd;exit;
        $user = $model->where('username', $name)->find();
        if (!empty($user) && md5($password.PWD_SIGN) == $user->password) {
            session('session_start_time', time());
            session('user_id', $user->id);
            Cache::set('user_info:'.$user->id, $user, config('session')['expire']);
        } else {
            $this->error('用户名或密码错误!', 'index', 1);
        }
        $this->redirect('Index/index');
    }

    public function logout()
    {
        $user_id = session('user_id');
        session(null);
        Cache::clear('user_info:'.$user_id);
        $this->redirect('index');
    }

    public function getCode()
    {
        $captcha = new Captcha();
        $code = $captcha->entry();
        return $code;
    }
}
