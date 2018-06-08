<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\Cache;
use think\App;
use app\common\model\Options;

function get_cfg($name, $default = '', $real = FALSE)
{
    $config = '';
    if (!$real && App::$debug != true) {
        $config = Cache::get('option_'.$name);
        return $config ?: get_cfg($name, $default, TRUE);
    } else {
        $model = Options::get(['option_name' => $name]);
        if (!empty($model)) {
            $config = $model->option_value;
            Cache::set('option_'.$name, $config, get_cfg('cache.expire'));
        }
        return $config ?: $default;
    }
}

function set_cfg($name, $value)
{
    $model = Options::get(['option_name' => $name]);
    if (empty($model)) {
        $model = new Options;
    }
    $model->option_name = $name;
    $model->option_value = $value;
    $model->save();
    Cache::set('option_'.$name, $value);
}

function obj2arr(&$data){
    if(is_object($data)){
        $json_data = json_encode($data);
        $data = json_decode($json_data,true);
    }
}
