<?php

# 缓存 - 全局
define('CACHE_ENABLE', true);    # 是否开启缓存
define('CACHE_KEY', '%s:%s:%s'); # 缓存KEY格式

# 缓存 - 从数据库取出的数据
define('CACHE_EXIST_EXPIRE', 3600 * 24 * 7);  # 找到资源缓存一周
define('CACHE_NOT_EXIST_EXPIRE', 3600 * 3 );  # 未找到资源缓存3小时

# 缓存 - BT
define('CACHE_BT_EXIST_EXPIRE', 3600 * 4);    # 找到资源缓存4小时，就是至少每隔4小时应该重新检查一次种子数
define('CACHE_BT_NOTE_EXIST_EXPIRE', 3600);   # 没有找到缓存1小时...只是防止被人反复刷罢了
define('CACHE_BT_SOURCE_ID', 1);              # 别名表中"bt"这个来源的tiny_int值

# 缓存 - 日志/统计
define('CACHE_STATISTICS_REQUEST_COUNT', 'statistics_rc');    # 总点击量
define('CACHE_STATISTICS_FOUND_COUNT', 'statistics_fc');      # 找到资源的点击量
define('CACHE_STATISTICS_NOT_FOUND_COUNT', 'statistics_nfc'); # 未找到资源的点击量
define('CACHE_STATISTICS_REQUEST_COUNT_MAX', 10);             # 缓存值写入数据库的阈值
define('CACHE_STATISTICS_EXPIRE', 3600 * 24 * 7);             # 过期时间

# 防止来自同一个客户端的重复请求刷点击量
define('CACHE_REPEAT_DELAY_KEY', '%s');    # KEY
define('CACHE_REPEAT_DELAY_TIME', 3);      # 不记录3s内来自同一客户端的请求

# 后台
define('ADMIN_COOKIE_EXPIRE', 3600 * 24 * 3); # 后台登录cookie过期时间
define('SHOW_LOG_OF_LAST_N_DAY', 5);          # 显示最近n天的日志

# 地址常量
define('KTXP_SEARCH', 'http://bt.ktxp.com/search.php?keyword=%s&sort_id=1&field=title&order=seeders'); # 极影搜索地址

# 外部提交请求时$source这个字段的所有可用值
$SOURCE_LIST = array('bili', 'bt');