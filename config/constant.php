<?php

# 缓存 - 全局
define('CACHE_KEY', '%s:%s:%s');  # 缓存KEY格式

# 缓存 - 从数据库取出的数据
define('CACHE_EXIST_EXPIRE', 3600 * 24 * 7);  # 找到资源缓存一周
define('CACHE_NOT_EXIST_EXPIRE', 3600 );      # 未找到资源缓存1小时

# 缓存 - BT
define('CACHE_BT_EXIST_EXPIRE', 3600 * 4);    # 找到资源缓存4小时，每隔4小时重新检查一次种子数
define('CACHE_BT_NOTE_EXIST_EXPIRE', 3600);   # 没有找到缓存1小时...只是防止被人反复刷罢了

# 防止来自同一个客户端的重复请求
define('CACHE_REPEAT_DELAY_KEY', '%s');    # KEY
define('CACHE_REPEAT_DELAY_TIME', 3);      # 两次请求间至少间隔3s

# 后台
define('ADMIN_COOKIE_EXPIRE', 3600 * 24 * 3); # 后台登录cookie过期时间
define('SHOW_LOG_OF_LAST_N_DAY', 5);          # 显示最近n天的日志

# 地址常量
define('KTXP_SEARCH', 'http://bt.ktxp.com/search.php?keyword=%s&sort_id=1&field=title&order=seeders'); # 极影搜索地址
define('CDN', 'http://thec-public.qiniudn.com/');         # CDN地址

# 目录常量
define('TEMPLATE_PATH', BASE_PATH . '/template');
define('TEMPLATE_BACKEND_PATH', TEMPLATE_PATH . '/backend/');
define('TEMPLATE_COMMON_PATH', TEMPLATE_PATH . '/common/');
define('TEMPLATE_FRONTEND_PATH', TEMPLATE_PATH . '/');
define('CONTROLLER_PATH', BASE_PATH . '/controller');
define('STATIC_PATH', '/static');

# 自动加载类的搜索目录
$autoloads = array('lib', 'common', 'controller');

# 导航结构
$BACKEND_NAV = array(
    'index'       => '统计',
    'log'         => '抓取记录',
    'addresource' => '关联资源'
);
$FRONTEND_NAV = array(

);

# 外部提交请求时$source这个字段的所有合法值
$SOURCE_LIST = array(
    'bili' => 0, 
    'bt'   => 9
);