<?php

namespace app\index\controller;

use app\common\model\Options;

class Index extends Base
{

    public function index()
    {
        $options = new Options;
        $list = $options->all();
        $this->assign('list', $list);
        $this->assign('title', '首页');
        $content = $this->fetch();
        return $content;
    }

    public function getLists()
    {
        $param = request()->param();
        $res = $this->requestApi($param['param']);
        exit(json_encode($res));
    }
}
