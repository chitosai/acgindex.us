<?php
require_once('auth.php');

// 操作按钮
function cache_manager() {
?>
    <div id="cache_manager">
        <b>清空全站memcached缓存：</b><a href="memcache.php?command=clear-all-cache">清空</a>
    </div>
<?php
}

// 清空全站缓存
function clear_all_cache() {
    check_auth();

    require_once('../tsukasa/utility.php');

    $cache = new CACHE();
    $cache->flush();

    echo '清空完毕';
}

if(isset($_GET['command'])) {
    switch($_GET['command']) {
        case 'clear-all-cache' : clear_all_cache(); break;
    }
}