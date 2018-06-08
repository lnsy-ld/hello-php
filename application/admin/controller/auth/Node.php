<?php

namespace app\admin\controller\auth;

use think\Cache;
use app\admin\controller\Base;
use app\admin\event\NodeEvent;

class Node extends Base
{

    public function __construct()
    {
        parent::__construct();
        $this->assign('module', '权限管理');
    }

    public function lists()
    {
        $nodes = NodeEvent::getNodes();
        if(empty($nodes)){
            NodeEvent::insertBase();
            $nodes = NodeEvent::getNodes();
        }
        $this->assign('action', '节点管理');
        $this->assign('page', '节点列表');
        $this->assign('list', $nodes[0]);
        $this->myAssign();
        return $this->myFetch();
    }

    public function getNodeInfo()
    {
        $param = request()->param();
        $node_id = $param['node_id'];
        $node = model('node')->get($node_id);
        return $node;
    }

    public function getBaseNode()
    {
        $where['level'] = 1;
        $where['pid'] = 0;
        $node = model('node')->where($where)->find();
        return $node;
    }

    public function rebuildNode()
    {
        NodeEvent::initAdmin();
        return true;
    }

    public function delete()
    {
        $param = request()->param();
        $node_id = $param['node_id'];
        $node = model('node')->get($node_id);
        $node->delete();
        $node->where('pid', $param['node_id'])->delete();
        return true;
    }

    public function saveNode()
    {
        $param = request()->param();
        $result = $this->validate(
                [
            'node' => $param['node'],
            'name' => $param['name'],
                ], [
            'node' => 'require',
            'name' => 'require',
                ]
        );
        if (true !== $result) {
            $this->error($result);
        }
        if ($param['node_id']) {
            $node = model('node')->get($param['node_id']);
        } else {
            $node = model('node');
        }

        $node->node = $param['node'];
        $node->name = $param['name'];
        $node->pid = $param['pid'];
        $node->status = $param['status'];
        $node->remark = $param['remark'];
        $node->level = $param['level'];
        $node->order_weight = $param['order_weight'];
        $user_id = session('user_id');
        $user = Cache::get('user_info:'.$user_id);
        $node->updator = $user->username;
        $node->save();
        return $node;
    }
}
