<?php
return [
    'menu_list' => [


        'site' => [
            'name' => '网站内容管理',
            'icon' => 'fa-align-justify',
            'childrens' => [
                'site.set/site_info' => '基本信息',
                'site.menu/menus' => '菜单管理',
                'site.link/link' => '友情链接',
                'site.page/single_page' => '单页内容管理',
                'site.video/index' => '视频管理',
            ],
        ],
        'article' => [
            'name' => '文章管理',
            'icon' => 'fa-book',
            'childrens' => [
                'article.category/index' => '文章分类',
                'article.article/lists?type=1' => '文章列表',
                'article.article/draft' => '草稿箱',
                'article.article/recycle' => '回收站',
            ]
        ],
        'advertise' => [
            'name' => '广告管理',
            'icon' => 'fa-audio-description',
            'childrens' => [
                'ad.loop/lists' => '首页轮播图管理',
                'ad.index/lists' => '首页广告管理',
            ]
        ],
        'database' => [
            'name' => '数据库管理',
            'icon' => 'fa-database ',
            'childrens' => [
                'database/backup' => '数据库备份',
                'database/import' => '数据库导入',
//                'database/compress' => '数据库压缩'
            ],
        ],
        'log' => [
            'name' => '日志管理',
            'icon' => 'fa-hand-pointer-o',
            'childrens' => [
                'log/action' => '操作日志',
                'log/login' => '登录日志',
            ]
        ],
        'crm' => [
            'hide' => true,
            'name' => '客服管理',
            'icon' => 'fa-user-secret',
            'childrens' => [
                'lists' => '客服列表',
                'add' => '添加客服',
            ]
        ],
        'customermessage' => [
            'hide' => true,
            'name' => '留言管理',
            'icon' => 'fa-comment',
            'childrens' => [
                'lists/type/all' => '留言列表',
                'lists/type/0' => '未回复',
            ]
        ],
        'auth' => [
            'name' => '权限管理',
            'icon' => 'fa-gear',
            'childrens' => [
                'auth.user/lists' => '用户管理',
                'auth.role/lists' => '角色管理',
                'auth.node/lists' => '节点管理',
            ]
        ],
        'system' => [
            'name' => '系统管理',
            'icon' => 'fa-gear',
            'childrens' => [
                'system/openRegister' => '开放注册',
                'system/clearCache' => '缓存清理',
                'system/database' => '数据库设置'
            ]
        ],
        'index' => [
            'name' => '欢迎登陆',
            'hide' => true,
            'childrens' => [
                'index' => '欢迎登录',
            ]
        ],
        'login' => [
            'hide' => true,
            'name' => '登录系统',
            'childrens' => [
                'index' => '登录',
                'login' => '登录',
                'logout' => '安全退出',
            ]
        ],
    ],
    'session' => [
        'prefix' => 'admin_',
        'type' => '',
        'auto_start' => true,
        'expire' => 3600
    ],
    'auth_ctr' => [
        'Index',
        'Site.set',
        'Site.menu',
        'Site.link',
        'Site.page',
        'Site.video',
        'Article.article', 
        'Article.category', 
        'Auth.node', 
        'Auth.role', 
        'Auth.user',
    ],
    'web_path' => '/'
];
