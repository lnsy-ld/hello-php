<?php

namespace app\index\controller;

use app\common\model\Options;

class Index extends Base
{

    public function index()
    {
        $options = new Options;
        $option = $options->all();
        $this->assign('option', $option);
        $this->assign('title', '首页');
        $this->attachTag();
        $this->attachArticle();
        $content = $this->fetch();
        return $content;
    }

    public function getLists()
    {
        $param = request()->param();
        $res = $this->requestApi($param['param']);
        exit(json_encode($res));
    }

    private function attachArticle()
    {
        $where['status'] = 1;
        $list = model('Article')->where($where)->order('is_top desc,update_time desc')->paginate(10);
        $this->assign('articles_num', $list->total());
        $this->assign('articles', $list);
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
