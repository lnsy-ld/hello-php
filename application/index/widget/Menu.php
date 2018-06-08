<?php

namespace app\index\widget;

use think\Controller;

class Menu extends Controller
{

    static protected $template;

    public function __construct()
    {
        parent::__construct();
    }
    
    public function banner(){
        return $this->fetch('widget/banner');
    }
    
    public function nav_menu(){
        return $this->fetch('widget/nav_menu');
    }
}
