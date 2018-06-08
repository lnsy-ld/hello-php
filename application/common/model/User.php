<?php

namespace app\common\model;

use think\Model;

class User extends Model
{

    public function role()
    {
        return $this->hasOne('role', 'role_id', 'role_id');
    }

    public function getUsers($where = [])
    {
        $where['id'] =['neq',1];
        $list = $this->where($where)->paginate(10);
        return $list;
    }

    public function getStatusAttr($value)
    {
        $status = [1 => '开启', 0 => '禁用'];
        return $status[$value];
    }
}
