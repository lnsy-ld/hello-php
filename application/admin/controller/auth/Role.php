<?php

namespace app\admin\controller\auth;

use think\Cache;
use app\admin\controller\Base;
use app\admin\event\NodeEvent;

class Role extends Base
{

    public function __construct()
    {
        parent::__construct();
        $this->assign('module', '权限管理');
    }

    public function lists()
    {

        $this->assign('action', '角色管理');
        $this->assign('page', '角色列表');
        $list = model('role')->getRoles();
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

    public function getRoleInfo()
    {
        $param = request()->param();
        $role_id = $param['role_id'];
        $role = model('role')->get($role_id);
        return $role;
    }

    public function saveRole()
    {
        $param = request()->param();

        $result = $this->validate(
                [
            'role_name' => $param['role_name'],
                ], [
            'role_name' => 'require',
                ]
        );
        if (true !== $result) {
            $this->error($result);
        }
        if ($param['role_id']) {
            $role = model('role')->get($param['role_id']);
        } else {
            $role = model('role');
        }

        $role->role_name = $param['role_name'];
        $role->status = $param['status'];
        $user_id = session('user_id');
        $user = Cache::get('user_info:'.$user_id);
        $role->creator = $user->username;
        $role->save();
        return $this->redirect('lists');
    }

    public function addRole()
    {
        $param = request()->param();
        $result = $this->validate(
                [
            'role_name' => $param['role_name'],
                ], [
            'role_name' => 'require',
                ]
        );
        if (true !== $result) {
            $this->error($result);
        }
        $role = model('role');

        $role->role_name = $param['role_name'];
        $role->status = $param['status'];
        $user_id = session('user_id');
        $user = Cache::get('user_info:'.$user_id);
        $role->creator = $user->username;
        $role->save();
        $this->redirect('lists');
    }

    public function del()
    {
        $param = request()->param();
        $role = model('role')->get($param['role_id']);
        $role->delete();
        return $role;
    }

    public function roleNode()
    {
        $param = request()->param();
        $nodes = NodeEvent::getNodes();

        $this->assign('role_id', $param['id']);
        $this->assign('action', '角色管理');
        $this->assign('page', '分配权限');
        $this->assign('list', $nodes[0]);
        $this->myAssign();
        return $this->myFetch();
    }

    public function saveNode()
    {
        $param = request()->param();
        $map = model('RoleNodeMap');
        $map->where('role_id', '=', $param['role_id'])->delete();
        $list = [];
        $nodes = array_keys($param['node']);
        foreach ($nodes as $val) {
            $tmp = [];
            $tmp['role_id'] = $param['role_id'];
            $tmp['node_id'] = $val;
            $list[] = $tmp;
        }
        $map->saveAll($list);
        $this->redirect('lists');
    }

    public function getRoleNodes()
    {
        $param = request()->param();
        $map = model('RoleNodeMap');
        $roleNode = $map->where('role_id', '=', $param['id'])->select();
        return $roleNode;
    }
}
