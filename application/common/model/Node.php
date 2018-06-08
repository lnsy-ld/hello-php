<?php

namespace app\common\model;

use think\Model;

class Node extends Model
{
    
    public function getNodes($where = [])
    {
        $list = $this->where($where)->order('order_weight desc')->select();
        return $list;
    }
    public function getStatusAttr($value)
    {
        $status = [1 => '开启', 0 => '禁用'];
        return $status[$value];
    }
    
    public function getParentAttr($value)
    {
        return self::get($value['pid']);
    }

        
}
