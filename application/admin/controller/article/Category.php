<?php

namespace app\admin\controller\article;

use app\admin\controller\Base;
use app\common\model\ArticleCategory;

class Category extends Base
{

    public function index()
    {
        $list = model('ArticleCategory')->getCategorys();
        $this->assign('list', $list);
        $this->assign('action', '文章分类');
        $this->assign('page', '列表');
        $this->myAssign();
        return $this->myFetch();
    }

    public function saveCategory()
    {
        $param = request()->param();
        $id = isset($param['id']) && $param ? $param['id'] : '';
        $cataroy = new ArticleCategory;
        if ($id > 0) {
            $where['id'] = $id;
            $cataroy->allowField(true)->save($param, $where);
        } else {
            $cataroy->allowField(true)->save();
        }
        return 1;
    }

    public function getCategoryInfo()
    {
        $params = request()->param();
        if (empty($params['id'])) {
            return ['error' => 'missing params'];
        }
        $cataroy = new ArticleCategory;
        $info = $cataroy->find($params['id']);
        return $info ?: [];
    }

    public function deletelink()
    {
        $params = request()->param();
        if (empty($params['id'])) {
            return ['error' => 'missing params'];
        }
        $cataroy = new ArticleCategory;
        $info = $cataroy->destroy($params['id']);
        return $info ?: [];
    }
}
