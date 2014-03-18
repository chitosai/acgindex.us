<?

define('BASE_PATH', __DIR__);

require 'config/config.php';
require 'config/constant.php';
require 'lib/autoload.php';

if( !isset($_GET['method']) ) {
    Core::r404();
}

# 任何请求都会用到缓存，于是先加载进来
if( CACHE_ENABLE ) {
    require 'lib/Cache.php';
    Cache::init();
}

# 判断身份
Core::Auth();

# 调用方法
require 'controller/backend.php';
$c = new Backend();
$method = $_GET['method'];

if( !method_exists( $c, $method ) )
    Core::r404();

$c->$method($_REQUEST);