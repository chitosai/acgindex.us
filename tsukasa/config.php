<?php

define('MC_EXIST_EXPIRE', 60 * 24 * 7);  # 一周
define('MC_NOT_EXIST_EXPIRE', 60 * 3 );  # 3小时

define('MC_STATISTICS_REQUEST_COUNT', 'statistics_rc');  # 缓存在MC中的{请求数量}
define('MC_STATISTICS_REQUEST_COUNT_MAX', 10);           # 缓存的{请求数量}写入数据库的阈值
define('MC_STATISTICS_EXPIRE', 60 * 24 * 7);                 # 缓存数据过期时间

define('ADMIN_COOKIE_EXPIRE', 3600 * 24 * 3); # 后台登录cookie过期时间
define('SHOW_LOG_OF_LAST_N_DAY', 5);          # 显示最近n天的日志

# 外部提交请求时$source这个字段的所有可用值
$SOURCE_LIST = array('bili');