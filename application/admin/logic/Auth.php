<?php

namespace app\admin\logic;

use think\Controller;
use think\Cache;
use app\admin\controller\Base;

class Auth extends Controller
{

    static public function checkUserAuth()
    {
        if (session('user_id') == '1') {
            return true;
        }
        $ctr = request()->controller();
        $isCheck = self::isCheckCtr($ctr);
        if (!$isCheck) {
            return TRUE;
        }
        $userNode = self::getUserNode();
        if (!$userNode) {
            return FALSE;
        }
        $ctrNode = self::getNode($ctr);
        if (!$ctrNode) {
            return FALSE;
        }
        $ctrNodeId = $ctrNode[0]->node_id;
        $passCtr = in_array($ctrNodeId, $userNode);
        if (!$passCtr) {
            return FALSE;
        }
        $mtd = request()->action();
        $mtdNode = self::getNode($mtd, $ctrNodeId);
        if (!$mtdNode) {
            return FALSE;
        }
        $mtdNodeId = $mtdNode[0]->node_id;
        $passMtd = in_array($mtdNodeId, $userNode);
        if (!$passMtd) {
            return FALSE;
        }
        return TRUE;
    }

    static private function getUserNode()
    {
        $user_id = session('user_id');
        $user_info = Cache::get('user_info:'.$user_id);
        if (!$user_info) {
            $base = new Base;
            $base->checkLogin();
        }
        if ($user_info['status'] != 1) {
            return FALSE;
        }
        $user_role = $user_info['role_id'];
        $where['role_id'] = $user_role;
        $role = model('role')->get($user_role);
        if ($role && $role['status'] != 1) {
            return FALSE;
        }
        $map = model('role_node_map')->where($where)->select();
        if (!$map) {
            return FALSE;
        }
        $count = count($map);
        $nodes = [];
        for ($i = 0; $i < $count; $i++) {
            $nodes[] = $map[$i]['node_id'];
        }
        return $nodes;
    }

    static private function isCheckCtr($ctr)
    {

        $checkCtr = config('auth_ctr');
        return in_array($ctr, $checkCtr);
    }

    static private function getNode($node, $ctrNodeId = null)
    {
        $where['node'] = $node;
        if ($ctrNodeId) {
            $where['pid'] = $ctrNodeId;
        }
        return model('node')->where($where)->select();
    }
}
