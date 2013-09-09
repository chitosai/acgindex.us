<?php

define('MC_KEY', '%s:%s:%s'); # 通用的KEY格式

define('MC_EXIST_EXPIRE', 3600 * 24 * 7);  # 一周
define('MC_NOT_EXIST_EXPIRE', 3600 * 3 );  # 3小时

define('MC_BT_EXIST_EXPIRE', 3600 * 4);  # BT种子存在缓存4小时，就是至少每隔4小时应该重新检查一次种子数
define('MC_BT_NOTE_EXIST_EXPIRE', 3600); # BT种子不存在缓存1小时...只是防止被人反复刷罢了
define('MC_BT_SOURCE_ID', 1);            # BT的source_type_id

define('MC_STATISTICS_REQUEST_COUNT', 'statistics_rc');  # 缓存在MC中的{请求数量}
define('MC_STATISTICS_REQUEST_COUNT_MAX', 10);           # 缓存的{请求数量}写入数据库的阈值
define('MC_STATISTICS_EXPIRE', 3600 * 24 * 7);           # {请求数量}缓存过期时间

define('ADMIN_COOKIE_EXPIRE', 3600 * 24 * 3);  # 后台登录cookie过期时间
define('SHOW_LOG_OF_LAST_N_DAY', 5);           # 显示最近n天的日志

define('KTXP_SEARCH', 'http://bt.ktxp.com/search.php?keyword=%s&sort_id=1&field=title&order=seeders'); # 极影搜索地址

# 外部提交请求时$source这个字段的所有可用值
$SOURCE_LIST = array('bili', 'bt');