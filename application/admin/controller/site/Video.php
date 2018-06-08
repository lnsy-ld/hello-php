<?php

namespace app\admin\controller\site;

use think\Db;
use app\admin\controller\Base;

class Video extends Base
{

    public function __construct()
    {
        header('Content-type:text/html;charset=utf-8');
        parent::__construct();
        $this->assign('module', '网站内容管理');
    }

    public function index()
    {
        $list = Db::name('video')->select();
        $this->assign('list_count', count($list));
        $this->assign('list', $list);
        $this->assign('action', '友情链接');
        $this->assign('page', '列表');
        $this->myAssign();
        return $this->myFetch();
    }

    public function edit()
    {
        $param= request()->param();
        $this->assign('id',$param['id']);
        $this->myAssign();
        return $this->myFetch();
    }

    public function saveVideo()
    {
        $param = request()->param();
        $file = request()->file('file');
        if ($file) {
            $info = $file->move(ROOT_PATH.'public'.DS.'uploads');
            if ($info) {
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $url = DS.'public'.DS.'uploads'.DS.$info->getSaveName();
                $video = $url;
            } else {
                // 上传失败获取错误信息
                echo $file->getError();
                exit;
            }
        }
        $data['video_name'] = $param['video_name'];
        $data['path'] = $video;
        Db::name('video')->insert($data);
        $this->redirect('index');
    }
}
