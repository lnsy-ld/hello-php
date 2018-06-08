<?php

namespace app\index\widget;

class Index extends Base
{

    public function index()
    {
        $content = $this->fetch('widget/banner');
        return $content;
    }
}
