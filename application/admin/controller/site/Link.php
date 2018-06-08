<?php

namespace app\admin\controller\site;

use think\Db;
use app\admin\controller\Base;

class Link extends Base
{

    public function __construct()
    {
        header('Content-type:text/html;charset=utf-8');
        parent::__construct();
        $this->assign('module', '网站内容管理');
    }

    public function link()
    {
        $list = Db::name('link')->order('order_weight desc')->select();
        foreach ($list as &$val) {
            $val['status_msg'] = $val['status'] ? '开启' : '关闭';
        }
        $this->assign('list_count', count($list));
        $this->assign('list', $list);
        $this->assign('action', '友情链接');
        $this->assign('page', '列表');
        $this->myAssign();
        return $this->myFetch();
    }

    public function getLinkInfo()
    {
        $params = request()->param();
        if (empty($params['id'])) {
            return ['error' => 'missing params'];
        }
        $info = Db::name('link')->where(['id' => $params['id']])->order('order_weight desc')->find();
        return $info? : [];
    }

    public function saveLink()
    {
        $param = request()->param();
        if (!empty($param['id'])) {
            $result = DB::name('link')->update($param);
        } else {
            $result = DB::name('link')->insert($param);
        }
        return $result;
    }
}
