<?php

define('MC_EXIST_EXPIRE', 60 * 24 * 7);  # 一周
define('MC_NOT_EXIST_EXPIRE', 60 * 3 );  # 3小时

# 外部提交请求时$source这个字段的所有可用值
$SOURCE_LIST = array('bili');