<?

if( !isset($_GET['controller']) || !isset($_GET['method']) ) {
    die('ACGINDEX CORE -HARUKA-');
}

define('BASE_PATH', __DIR__);

require 'config/config.php';
require 'config/constant.php';
require 'lib/autoload.php';

# 任何请求都会用到缓存，于是先加载进来
if( CACHE_ENABLE ) {
    require 'lib/Cache.php';
    Cache::init();
}

$controller = $_GET['controller'];
$method = $_GET['method'];

$c = new $controller();
$c->$method($_REQUEST);