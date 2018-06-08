<?php

namespace app\admin\controller;

use think\Db;

class Advertise extends Base
{

    public function loop_image()
    {
        $list = Db::name('ad_images')->where(['type' => 1])->paginate(PHP_INT_MAX)->each(function($item) {
            $item['status_msg'] = $item['status'] == 1 ? '开启' : '关闭';
            return $item;
        });
        $this->assign('list', $list);
        $this->assign('action', '广告管理');
        $this->assign('page', '首页轮播图');
        $this->myAssign();
        return $this->myFetch();
    }

    public function home_ad()
    {
        $this->fetch();
    }

    public function getImageInfo()
    {
        $params = request()->param();
        if (empty($params['id'])) {
            return ['error' => 'missing params'];
        }
        $info = Db::name('ad_images')->where(['id' => $params['id']])->order('order_weight desc')->find();
        return $info? : [];
    }

    public function saveImage()
    {
        $param = request()->param();
        $file = request()->file('ad_image');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            $uploads_path = ROOT_PATH.'public'.DS.'uploads';
            $info = $file->move($uploads_path);
            if ($info) {
                $path = DS.'tkphp5'.DS.'public'.DS.'uploads'.DS.$info->getSaveName();
                $data['image_path'] = $path;
                $data['id'] = $param['image_id'];
                $id = Db::name('ad_images')->update($data);
                if ($id > 0) {
                    return $this->redirect('loop_image');
                }
            } else {
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }
}
