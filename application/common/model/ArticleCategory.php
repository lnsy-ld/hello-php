<?php

namespace app\common\model;

use think\Model;

class ArticleCategory extends Model
{
    
    public function articles(){
         return $this->hasMany('article','category_id');
    }
    public function getCategoryById($id)
    {
        $obj = $this->get($id);
        return $obj;
    }
    
    public function getCategorys($where=[])
    {
        $list = $this->where($where)->order('order_weight','desc')->paginate();
        return $list;
    }
    
    public function getStatusAttr($value)
    {
        $status = [1=>'开启',0=>'关闭'];
        return $status[$value];
    }
    
    public function getArticleNumAttr(){
        return $this->articles()->count();
    }
}
