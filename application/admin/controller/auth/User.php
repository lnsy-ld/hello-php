<?php

namespace app\admin\controller\auth;

use app\admin\controller\Base;

class User extends Base
{

    public function __construct()
    {
        parent::__construct();
        $this->assign('module', '权限管理');
    }

    public function lists()
    {
        $this->assign('action', '管理员管理');
        $this->assign('page', '管理员列表');
        $list = model('user')->getUsers();
        $this->assign('list', $list);
        $this->myAssign();
        return $this->myFetch();
    }

    public function add()
    {
        $this->assign('action', '管理员管理');
        $this->assign('page', '添加管理员');
        $roles = model('role')->all();
        $this->assign('roles', $roles);
        $this->myAssign();
        return $this->myFetch();
    }

    public function edit()
    {
        $this->assign('action', '管理员管理');
        $this->assign('page', '编辑管理员信息');
        $param = request()->param();
        $user_id = $param['id'];
        $user = model('user')->get($user_id);
        $roles = model('role')->all();
        $this->assign('roles', $roles);
        $this->assign('info', $user);
        $this->myAssign();
        return $this->myFetch();
    }

    public function add_user()
    {
        $param = request()->param();
        $user = model('user');
        
        $result = $this->validate(
                [
            'username' => $param['username'],
            'nickname' => $param['nickname'],
            'password' => $param['password'],
            'repassword' => $param['repassword'],
                ], [
            'username' => 'require|length:4,25|alphaDash',
            'nickname' => 'require|length:1,25|chsAlphaNum',
            'password' => 'require|length:4,25|AlphaNum',
            'repassword' => 'require|confirm:password',
                ]
        );
        if (true !== $result) {
            $this->error($result);
        }
        $where['username'] = $param['username'];
        $check = $user->where($where)->find();
        if($check){
            $this->error('用户已存在!');
        }
        $user->username = $param['username'];
        $user->nickname = $param['nickname'];
        $user->password = md5($param['password'].PWD_SIGN);
        $user->status = $param['status'];
        $user->role_id = $param['role_id'];
        $user->save();
        $this->redirect('lists');
    }

    public function save_user()
    {
        $param = request()->param();
        $user = model('user')->get($param['id']);
        $result = $this->validate(
                [
            'username' => $param['username'],
            'nickname' => $param['nickname'],
                ], [
            'username' => 'require|length:4,25|alphaDash',
            'nickname' => 'require|length:1,25|chsAlphaNum',
                ]
        );
        if (true !== $result) {
            $this->error($result);
        }
        $user->username = $param['username'];
        $user->nickname = $param['nickname'];
        $user->status = $param['status'];
        $user->role_id = $param['role_id'];
        $user->save();
        $this->redirect('lists');
    }

    public function change_pwd()
    {
        $param = request()->param();
        $user = model('user')->get($param['id']);
        $result = $this->validate(
                [
            'password' => $param['password'],
            'repassword' => $param['repassword'],
                ], [
            'password' => 'require|length:4,25|chsAlphaNum',
            'repassword' => 'require|confirm:password',
        ]);
        if (true !== $result) {
            dump($result);
            exit;
        }
        $user->password = md5($param['password'].PWD_SIGN);
        $user->save();
    }

    public function del()
    {
        $param = request()->param();
        if ($param['id'] == 1) {
            return '不可删除超级管理员';
        }
        $user = model('user')->get($param['id']);
        $user->delete();
        return $user;
    }
}
