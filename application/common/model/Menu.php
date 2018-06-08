<?php

namespace app\model;

use think\Model;
use think\Db;
class Menu extends Model
{

    protected $table = 'think_menus';

    public function getMenuTree()
    {
        $list = Db::name('menus')->paginate(PHP_INT_MAX)->each(function($item) {
            $item['status_msg'] = $item['status'] == 1 ? '开启' : '关闭';
            return $item;
        });
        $menus['data'] = $this->getTree($list);
        $menus['count'] = Db::name('menus')->count();
        return $menus;
    }
    
    public function getNav()
    {
        $list = Db::name('menus')-> where('status=1')->order('order_weight')->paginate(PHP_INT_MAX)->each(function($item) {
            $item['status_msg'] = $item['status'] == 1 ? '开启' : '关闭';
            return $item;
        });
        $menus = $this->getTree($list);
        return $menus;
    }
    
    private function getTree($list, $pid = 0)
    {
        $children = [];
        foreach ($list as $value) {
            if ($value['parent_id'] == $pid) {
                $children[] = $value;
            }
        }
        foreach($children as $key => $val){
            $children[$key]['childrens'] = $this->getTree($list,$val['menu_id']);
        }
        return $children;
    }
}
