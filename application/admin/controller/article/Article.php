<?php

namespace app\admin\controller\article;

use think\Db;
use app\admin\controller\Base;

class Article extends Base
{

    public function lists()
    {
        $where['status'] = 1;
        $list = Db::name('article')->where($where)->order('is_top desc,update_time desc')->paginate(10)->each(function($item, $key) {
            $item['is_top_msg'] = $item['is_top'] ? '是' : '否';
            return $item;
        });
        $this->assign('list_count', count($list));
        $this->assign('list', $list);
        $this->assign('action', '文章管理');
        $this->assign('page', '已发布文章列表');
        $this->myAssign();
        return $this->myFetch();
    }

    public function edit()
    {
        $category = Db::name('article_category')->order('order_weight desc')->select();
        $param = request()->param();
        $type = $param['type'];
        $info = Db::name('article')->where('id', $param['id'])->order('is_top desc')->find();
        $this->assign('type', $type);
        $this->assign('category', $category);
        $this->assign('info', $info);
        $this->myAssign();
        return $this->myFetch();
    }

    public function save()
    {
        $param = request()->param();
        $param['content'] = $param['editorValue'];
        $type = $param['type']? : 'lists';
        if ($param['id'] > 0) {
            Db::name('article')->where('id',$param['id'])->update($param);
        } else {
            Db::name('article')->insert($param);
        }
        $this->redirect($type);
    }

    public function draft()
    {
        $where['status'] = 0;
        $list = Db::name('article')->where($where)->order('is_top desc,update_time desc')->paginate(10)->each(function($item, $key) {
            $item['is_top_msg'] = $item['is_top'] ? '是' : '否';
            return $item;
        });
        $this->assign('list_count', count($list));
        $this->assign('list', $list);
        $this->assign('action', '文章管理');
        $this->assign('page', '草稿箱');
        $this->myAssign();
        return $this->myFetch();
    }

    public function recycle()
    {
        $where['status'] = 2;
        $list = Db::name('article')->where($where)->order('is_top desc,update_time desc')->paginate(10)->each(function($item, $key) {
            $item['is_top_msg'] = $item['is_top'] ? '是' : '否';
            return $item;
        });
        $this->assign('list_count', count($list));
        $this->assign('list', $list);
        $this->assign('action', '文章管理');
        $this->assign('page', '回收站');
        $this->myAssign();
        return $this->myFetch();
    }
}
