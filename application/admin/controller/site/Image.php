<?php

namespace app\admin\controller\site;

use think\Db;
use app\admin\controller\Base;

class Image extends Base
{

    public function __construct()
    {
        header('Content-type:text/html;charset=utf-8');
        parent::__construct();
        $this->assign('module', '网站内容管理');
    }

    public function index()
    {
        $list = Db::name('image')->select();
        $this->assign('list_count', count($list));
        $this->assign('list', $list);
        $this->assign('action', '图片管理');
        $this->assign('page', '图片列表');
        $this->myAssign();
        return $this->myFetch();
    }

    public function edit()
    {
        $param = request()->param();
        $this->assign('id', $param['id']);
        $this->myAssign();
        return $this->myFetch();
    }

    public function saveImage()
    {
        $file = request()->file('file');
        if ($file) {
            $info = $file->move(ROOT_PATH.'public'.DS.'uploads');
            if ($info) {
                $url = DS.'public'.DS.'uploads'.DS.$info->getSaveName();
                $image = $url;
            } else {
                echo $file->getError();
                exit;
            }
        }
        $data['image_path'] = $image;
        Db::name('image')->insert($data);
        $this->redirect('index');
    }

    public function deleteImage()
    {
        $params = request()->param();

        if (empty($params['id'])) {
            return ['error' => 'missing params'];
        }
        $info = Db::name('image')->where('id', $params['id'])->find();
        if ($info  && unlink('/var/www'.$info['image_path'])) {
            $res = db('image')->delete($params['id']);
            exit(json_encode($res, JSON_UNESCAPED_UNICODE));
        }
        exit(json_encode('error', JSON_UNESCAPED_UNICODE));
    }
}
