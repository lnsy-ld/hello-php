<?php

namespace app\index\controller;

class Article extends Base
{

    public function index()
    {
        $param = request()->param();
        $id = (int)$param['id'];
        $info = model('Article')->getArticleById($id);
        obj2arr($info);
        $this->assign('info',$info);
        $content = $this->fetch();
        return $content;
    }
}
