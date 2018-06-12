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
        $where['status'] = 1;
        $list = model('Article')->where($where)->order('is_top desc,update_time desc')->paginate(10);
        $category = model('ArticleCategory')->getCategorys();
        $this->assign('category_num', $category->total());
        $this->assign('articles_num', $list->total());
        $this->assign('access_num', '5');
        return $this->fetch('widget/userinfo');
    }

    public function tags()
    {
        $this->attachTag();
        return $this->fetch('widget/tags');
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
