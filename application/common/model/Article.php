<?php

namespace app\common\model;

use think\Model;

class Article extends Model
{
    public function getArticleById($id)
    {
        $where['id'] = $id;
        $obj = $this->get($where);
        return $obj;
    }
    
    public function getArticles($where=[])
    {
        $list = $this->where($where)->paginate();
        return $list;
    }
    
    public function getStatusAttr($value)
    {
        $status = [1=>'开启',0=>'关闭'];
        return $status[$value];
    }
    
    public function getNumByCategory(){
        $data = $this->group('category_id')->select();
        return $data;
    }

}
