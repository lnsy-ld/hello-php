<?php

namespace app\admin\controller;

use think\Controller;
use think\Cache;
use app\admin\logic\Auth;

class Base extends Controller
{

    protected $request;

    public function __construct()
    {
        header('Content-type:text/html;charset=utf-8');
        parent::__construct();
        $this->checkLogin();
        $this->checkAuth();
        $this->assignUser();
        $this->assignSideMenu();
    }

    protected function checkAuth()
    {
        if (!Auth::checkUserAuth()) {
            $msg['code'] = "500";
            $msg['msg'] = '没有权限!';
            if (request()->isAjax()) {
                exit(json_encode($msg, JSON_UNESCAPED_UNICODE));
            }
            echo "<script>alert('您没有权限!');setTimeout(function(){javascript:history.back(-1);},500)</script>";
            exit;
        }
    }

    protected function assignUser()
    {
        $user_id = session('user_id');
        $user_info = Cache::get('user_info:'.$user_id);
        if (!$user_info) {
            $user_info = model('user')->get($user_id);
        } else {
            Cache::set('user_info:'.$user_id, $user_info, config('session')['expire']);
        }
        $this->assign('user_info', $user_info);
    }

    protected function assignSideMenu()
    {
        $menuList = config('menu_list');
        $userNode = \app\admin\event\NodeEvent::getUserNodes();
        $showSystem = [];
        foreach ($menuList as $system => $value) {
            if (!isset($value['hide'])) {
                foreach ($value['childrens'] as $key => $val) {
                    if (!in_array($key, $userNode) && $this->getUserData()['id'] != 1) {
                        unset($value['childrens'][$key]);
                    }
                }
                if (count($value['childrens']) > 0) {
                    $showSystem[$system] = $value;
                }
            }
        }
        $this->assign('side_menu', $showSystem);
    }

    public function checkLogin()
    {
        $user_id = session('user_id');
        $user = Cache::get('user_info:'.$user_id);
        $cacheCheck = empty($user);//为真重新登录
        $timeCheck = (time() - session('session_start_time')) > config('session')['expire'];// 为真重新登录
        $ctrCheck = $this->request->controller() != 'Login';// 为真时检查登录
        $check = $cacheCheck ?: $timeCheck;
        if ($ctrCheck && $check) {
            session_destroy();//真正的销毁在这里！
            Cache::clear('user_info:'.$user_id);
            $this->error('登录失效，请重新登录!', 'login/index');
        } else {
            session('session_start_time', time());
            Cache::set('user_info:'.$user_id, $user, config('session')['expire']);
        }
    }

    protected function myAssign($page = false)
    {
        if (empty($page)) {
            $page = $this->request->action();
        }
        $this->assign('content', $this->fetch($page));
    }

    protected function myFetch()
    {
        return $this->fetch('show/board');
    }

    private function getUserData()
    {
        $user_id = session('user_id');
        $user = Cache::get('user_info:'.$user_id);
        return $user;
    }
}
