<?php

namespace app\admin\controller;

use think\Db;

class Database extends Base
{

    public function backup()
    {
        $table_list = Db::query('SHOW TABLE STATUS');
        foreach ($table_list as &$val) {
            $val['Data_length'] = $this->getFilesize($val['Data_length']);
            $val['Index_length'] = $this->getFilesize($val['Index_length']);
        }
        $this->assign('list', $table_list);
        $this->assign('action', '数据库备份');
        $this->assign('page', '数据表');
        $this->myAssign();
        return $this->myFetch();
    }
    
    public function import()
    {
        $this->assign('action', '数据库导入');
        $this->assign('page', '数据表');
        $this->myAssign();
        return $this->myFetch();
    }

    private function getFilesize($num)
    {
        if($num==0){
            return $num;
        }
        $p = 0;
        $format = 'bytes';
        if ($num > 0 && $num < 1024) {
            $p = 0;
            return number_format($num).' '.$format;
        }
        if ($num >= 1024 && $num < pow(1024, 2)) {
            $p = 1;
            $format = 'KB';
        }
        if ($num >= pow(1024, 2) && $num < pow(1024, 3)) {
            $p = 2;
            $format = 'MB';
        }
        if ($num >= pow(1024, 3) && $num < pow(1024, 4)) {
            $p = 3;
            $format = 'GB';
        }
        if ($num >= pow(1024, 4) && $num < pow(1024, 5)) {
            $p = 3;
            $format = 'TB';
        }
        $num /= pow(1024, $p);
        return $num.' '.$format;
    }
}
