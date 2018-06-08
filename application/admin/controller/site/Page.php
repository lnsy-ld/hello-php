<?php

namespace app\admin\controller\site;

use app\admin\controller\Base;
use think\Db;

class Page extends Base
{

    public function __construct()
    {
        header('Content-type:text/html;charset=utf-8');
        parent::__construct();
        $this->assign('module', '网站内容管理');
    }

    public function single_page()
    {
        $list = Db::name('pages')->order('update_time desc')->select();
        foreach ($list as &$val) {
            unset($val['content']);
        }
        $this->assign('list_count', count($list));
        $this->assign('list', $list);
        $this->assign('action', '单页内容管理');
        $this->assign('page', '页面列表');
        $this->myAssign();
        return $this->myFetch();
    }

    public function del()
    {
        $param = request()->param();
        $id = $param['id'];
        if (is_numeric($id) && $id > 0) {
            $result = Db::name('pages')->delete($id);
            return $result;
        }
        return FALSE;
    }

    public function edit()
    {
        $param = request()->param();
        $id = $param['id'];
        $info = [];
        if (is_numeric($id) && $id > 0) {
            $info = Db::name('pages')->find($id);
        }
        $this->assign('id',$id);
        $this->assign('info', $info);
        $this->assign('action', '单页内容管理');
        $this->assign('page', '编辑');
        $this->myAssign();
        return $this->myFetch();
    }

    public function savepage()
    {
        $param = request()->param();
        $param['content'] = $param['editorValue'];
        unset($param['editorValue']);
        if (empty($param['id'])) {
            Db::name('pages')->insert($param);
        }else{
            Db::name('pages')->update($param);
        }
        $this->redirect('single_page');
    }
}
