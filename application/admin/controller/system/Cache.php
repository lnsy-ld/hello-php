<?php

namespace app\admin\controller\system;

use app\admin\controller\Base;

class Cache extends Base
{

    public function __construct()
    {
        parent::__construct();
        $this->assign('module', '系统管理');
    }

    public function clearCache()
    {
        exit(json_encode(1, JSON_UNESCAPED_UNICODE));
        $this->assign('action', '网站基本信息');
        $this->assign('page', '设置');
        $list = [
            [
                'name' => '页面缓存',
                'path' => CACHE_PATH,
                'size' => filesize(TEMP_PATH)
            ]
        ];
        $this->assign('list',$list);
        $this->myAssign();
        return $this->myFetch();
    }
}
