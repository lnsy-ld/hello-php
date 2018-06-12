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
        $category = array_column($list['data'],'name');
        $this->assign('page_keywords',implode(',',$category));
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
        $list = model('Article')->where($where)->order('is_top desc,update_time desc')->paginate(10);
        $this->assign('articles_num',$list->total());
        $this->assign('articles', $list);
        $this->assign('title', '首页');
        $this->attachTag($id);
        $content = $this->fetch();
        return $content;
    }
    
    private function attachTag($id){
        $category  = model('ArticleCategory')->getCategorys();
        $this->assign('category_num',$category->total());
        obj2arr($category);
        $cates = array_column($category['data'], null,'id');
        $this->assign('tag',$cates[$id]['name']);
    }

}
