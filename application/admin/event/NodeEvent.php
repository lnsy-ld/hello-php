<?php

namespace app\admin\event;

use think\Controller;
use think\Cache;
use app\common\model\Node;
use app\common\model\RoleNodeMap;

class NodeEvent extends Controller
{

    static public function initAdmin()
    {
        $nodes = new Node;
        $nodes->where('node_id', 'neq', 0)->delete();
        $baseId = self::insertBase();
        self::setControlers($baseId);
        self::setMethods();
        self::clearMap();
    }

    private static function clearMap()
    {
        $map = new RoleNodeMap;
        $map->where('map_id', 'neq', 0)->delete();
    }

    private static function setControlers($baseId)
    {
        $controllers = config('auth_ctr');
        $user_id = session('user_id');
        $user = Cache::get('user_info:'.$user_id);
        $username = $user->username;
        foreach ($controllers as $value) {
            $data = [];
            $data['node'] = $value;
            $data['name'] = $value;
            $data['remark'] = '控制器';
            $data['status'] = 1;
            $data['order_weight'] = 0;
            $data['pid'] = $baseId;
            $data['level'] = 2;
            $data['updator'] = $username;
            $list[] = $data;
        }
        $nodes = new Node;
        $nodes->saveAll($list);
    }

    private static function setMethods()
    {
        $baseMethods = get_class_methods(new \app\admin\controller\Base());
        //消除继承得到的方法
        $Index = array_diff(get_class_methods(new \app\admin\controller\Index()), $baseMethods);
        $Auth_role = array_diff(get_class_methods(new \app\admin\controller\auth\Role()), $baseMethods);
        $Auth_node = array_diff(get_class_methods(new \app\admin\controller\auth\Node()), $baseMethods);
        $Auth_user = array_diff(get_class_methods(new \app\admin\controller\auth\User()), $baseMethods);
        $Article_article = array_diff(get_class_methods(new \app\admin\controller\article\Article()), $baseMethods);
        $Article_category = array_diff(get_class_methods(new \app\admin\controller\article\Category()), $baseMethods);
        $Site_set = array_diff(get_class_methods(new \app\admin\controller\site\Set()), $baseMethods);
        $Site_video = array_diff(get_class_methods(new \app\admin\controller\site\Video()), $baseMethods);
        $Site_menu = array_diff(get_class_methods(new \app\admin\controller\site\Menu()), $baseMethods);
        $Site_page = array_diff(get_class_methods(new \app\admin\controller\site\Page()), $baseMethods);
        $Site_link = array_diff(get_class_methods(new \app\admin\controller\site\Link()), $baseMethods);

        $user_id = session('user_id');
        $user = Cache::get('user_info:'.$user_id);
        $username = $user->username;

        $where['level'] = 2;
        $controller = Node::where($where)->select();
        $methods = [];
        foreach ($controller as $value) {
            $pid = $value['node_id'];
            $fullCtr = explode('.', $value['node']);
            $ctr = isset($fullCtr[1]) ? '_'.$fullCtr[1] : '';
            $ctr = $fullCtr[0].$ctr;
            foreach ($$ctr as $val) {
                $data = [];
                $data['node'] = $val;
                $data['name'] = $val;
                $data['remark'] = '操作';
                $data['status'] = 1;
                $data['order_weight'] = 0;
                $data['pid'] = $pid;
                $data['level'] = 3;
                $data['updator'] = $username;
                $methods[] = $data;
            }
        }
        $nodes = new Node;
        $nodes->saveAll($methods);
    }

    public static function insertBase()
    {
        $node = model('node');
        $node->node_id = 1;
        $node->node = '后台管理';
        $node->name = '后台管理';
        $node->remark = '后台管理';
        $node->level = 1;
        $node->pid = 0;
        $node->order_weight = 0;
        $node->status = 1;
        $node->save();
        return $node->node_id;
    }

    public static function getNodes($where=[])
    {
        $where['level'] = 1;
        $where['pid'] = 0;
        $baseNode = self::getChildNodes();
        return $baseNode;
    }

    private static function getChildNodes($pid = 0,$where=[])
    {
        $where['pid'] = $pid;
        $childs = model('node')->getNodes($where);
        if ($childs) {
            foreach ($childs as &$value) {
                $value['childs'] = self::getChildNodes($value['node_id']);
            }
            return $childs;
        } else {
            return [];
        }
    }

    public static function getUserNodes()
    {
        $user_id = session('user_id');
        $user = Cache::get('user_info:'.$user_id);
        $userRole = $user['role_id'];
        $where['role_id'] = $userRole;
        $nodes = model('role_node_map')->where($where)->select();
        $nodeIds = [];
        foreach($nodes as $val){
            $nodeIds[] = $val['node_id'];
        }
        $userWhere['node_id'] = ['in',$nodeIds];
        $userNode = self::getNodes($userWhere);
        $userNodes = [];
        if(empty($userNode)){
            return [];
        }
        foreach($userNode[0]['childs'] as $value){
            foreach($value['childs'] as $val){
                $userNodes[] = strtolower($value['node']).'/'.$val['node'];
            }
        }
        return $userNodes;
    }
}
