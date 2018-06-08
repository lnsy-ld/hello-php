<?php

namespace app\admin\controller\site;

use app\admin\controller\Base;
use think\Db;

class Menu extends Base
{

    public function __construct()
    {
        header('Content-type:text/html;charset=utf-8');
        parent::__construct();
        $this->assign('module', '网站内容管理');
    }

    public function menus()
    {
        $templates = ['goods_list' => '产品列表模板', 'goods_detail' => '产品详情模板'];
        $menus = $this->getMenuList();
        $this->assign('templates', $templates);
        $this->assign('list', $menus);
        $this->assign('action', '菜单管理');
        $this->assign('page', '菜单浏览');
        $this->myAssign();
        return $this->myFetch();
    }

    public function getMenuInfo()
    {
        $params = request()->param();
        if (empty($params['menu_id'])) {
            return ['code'=>500,'msg' => 'missing params'];
        }
        $info = Db::name('menus')->where(['menu_id' => $params['menu_id']])->order('order_weight desc')->find();
        return $info? : [];
    }

    public function saveMenu()
    {
        $param = request()->param();
        if (!empty($param['menu_id'])) {
            $result = DB::name('menus')->update($param);
        } else {
            $result = DB::name('menus')->insert($param);
        }
        return $result;
    }

    public function deleteMenu()
    {
        $param = request()->param();
        if (isset($param['menu_id']) > 0) {
            $menu_id = $param['menu_id'];
        } else {
            return ['code'=>500,'msg' => 'missing params'];
        }
        $childrens = Db::name('menus')->where(['parent_id' => $menu_id])->select();
        if (!empty($childrens)) {
            return ['code'=>500,'msg' => '当前菜单有子菜单，不可删除!'];
        }
        $info = Db::name('menus')->delete($menu_id);
        if ($info > 0) {
            return $info;
        } else {
            return ['code'=>500,'msg' => '发生错误，删除失败'];
        }
    }

    public function getMenuList($parent_id = 0)
    {
        $list = Db::name('menus')->where(['parent_id' => $parent_id])->order('order_weight desc')->select();
        foreach ($list as $key => $val) {
            $list[$key]['status_msg'] = $val['status'] ? '开启' : '关闭';
            $list[$key]['target_msg'] = $val['target_type'] == '_blank' ? '新窗口' : '当前窗口';
            $haveChildren = Db::name('menus')->where(['parent_id' => $val['menu_id']])->find();
            if (!empty($haveChildren)) {
                $list[$key]['childrens'] = $this->getMenuList($val['menu_id']);
            } else {
                $list[$key]['childrens'] = [];
            }
        }
        return $list;
    }

}
