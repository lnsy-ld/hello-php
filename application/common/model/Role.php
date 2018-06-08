<?php

namespace app\common\model;

use think\model\Merge;

class Role extends Merge
{

    public function user()
    {
        return $this->hasMany('user');
    }

    public function getRoles($where = [])
    {
        $list = $this->where($where)->paginate()->each(function($item) {
            $item['user_num'] = '';
            return $item;
        });
        return $list;
    }

    public function getStatusAttr($value)
    {
        $status = [1 => '开启', 0 => '禁用'];
        return $status[$value];
    }

    public function getUserNumAttr()
    {
        return $this->user()->count();
    }
}
