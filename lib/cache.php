<?

class CACHE { 
    static private $mmc = null; 
    static function init() { 
        self::$mmc = new memcache(); 
        @self::$mmc->connect( CACHE_HOST, CACHE_PORT ) or API::error('Memcache init failed');
    }
    static function set($key, $var, $expire = 3600) { 
        if(!self::$mmc) return false; 
        return self::$mmc->set($key, $var, 0, $expire); 
    } 
    static function get($key) {
        if(!self::$mmc) return false; 
        return self::$mmc->get($key); 
    } 
    static function has($key) {
        if(!self::$mmc) return false; 
        if( self::$mmc->get($key) ) return true;
        else return false;
    }
    static function incr($key, $value=1) { 
        if(!self::$mmc) return false; 
        return self::$mmc->increment($key, $value); 
    } 
    static function decr($key, $value=1) { 
        if(!self::$mmc) return false; 
        return self::$mmc->decrement($key, $value); 
    } 
    static function delete($key) { 
        if(!self::$mmc) return false; 
        return self::$mmc->delete($key); 
    }
    static function flush() {
        if(!self::$mmc) return false; 
        return self::$mmc->flush();
    }
    static function stats() {
        if(!self::$mmc) return false;
        API::success(self::$mmc->getStats());
    }
}