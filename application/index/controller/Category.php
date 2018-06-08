<?php

namespace app\index\controller;

use think\Db;

class Category extends Base
{
    
    public function __construct()
    {
        parent::__construct();
        header('Content-type:text/html;charset=utf-8');
        }

    public function index()
    {
        $where['status'] = 1;
        $list = model('ArticleCategory')->getCategorys($where);
        obj2arr($list);
        $this->assign('action', '文章分类');
        $this->assign('page', '列表');
        $this->assign('list', $list['data']);
        $this->assign('title', '首页');
        $content = $this->fetch();
        return $content;
    }
    
    public function alist()
    {
        $param = request()->param();
        $id = $param['id'];
        $where['category_id'] = $id;
        $where['status'] = 1;
        $list = model('Article')->getArticles($where);
        obj2arr($list);
        $this->assign('list', $list);
        $this->assign('title', '首页');
        $content = $this->fetch();
        return $content;
    }

}
