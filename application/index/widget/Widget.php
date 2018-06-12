<?php

namespace app\index\widget;

use think\Controller;

class Widget extends Controller
{

    static protected $template;

    public function __construct()
    {
        parent::__construct();
    }

    public function banner()
    {
        return $this->fetch('widget/banner');
    }

    public function nav_menu()
    {
        return $this->fetch('widget/nav_menu');
    }

    public function userinfo()
    {
        $this->redis->connect('127.0.0.1', 6379);
        $where['status'] = 1;
        $list = model('Article')->where($where)->order('is_top desc,update_time desc')->paginate(10);
        $category = model('ArticleCategory')->getCategorys();
        $this->assign('category_num', $category->total());
        $this->assign('articles_num', $list->total());
        $access_num = $this->redis->get('site_access_count');
        $this->assign('access_num', $access_num);
        return $this->fetch('widget/userinfo');
    }

    public function tags()
    {
        $this->attachTag();
        return $this->fetch('widget/tags');
    }
    
    public function monkey()
    {
        return $this->fetch('widget/monkey');
    }

    private function attachTag()
    {
        $category = model('ArticleCategory')->getCategorys();
        $this->assign('category_num', $category->total());
        obj2arr($category);
        $cates = array_column($category['data'], null, 'id');
        $this->assign('tag', $cates);
    }
}
