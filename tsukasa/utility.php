<?php

require_once('accounts.php');
require_once('config.php');

/* 
 * 缓存操作类，现在用的是Memcache，不过考虑到以后可能会更换底层，所以封装的时候还是用CACHE这样抽象的名字。
 * 以后换缓存的话只要保持方法的接口一致就OK了
 *
 */
class CACHE { 
    private $mmc = null; 
    function __construct() { 
        if( !CACHE_ENABLE ) return;
        $this->mmc = new memcache(); 
        $this->mmc->connect( CACHE_HOST, CACHE_PORT ) or die('Memcache init failed');
    }
    function __destruct() {
        if(!$this->mmc) return false;
        return $this->mmc->close();
    }
    function set($key, $var, $compress = MEMCACHE_COMPRESSED, $expire = 3600) { 
        if(!$this->mmc) return false; 
        return $this->mmc->set($key, $var, $compress, $expire); 
    } 
    function get($key) { 
        if(!$this->mmc) return false; 
        return $this->mmc->get($key); 
    } 
    function incr($key, $value=1) { 
        if(!$this->mmc) return false; 
        return $this->mmc->increment($key, $value); 
    } 
    function decr($key, $value=1) { 
        if(!$this->mmc) return false; 
        return $this->mmc->decrement($key, $value); 
    } 
    function delete($key) { 
        if(!$this->mmc) return false; 
        return $this->mmc->delete($key); 
    }
    function flush() {
        if(!$this->mmc) return false; 
        return $this->mmc->flush();
    }
    function stats() {
        if(!$this->mmc) return false;
        var_dump($this->mmc->getStats());
    }
}


/*
 * 计时器
 *
 */
class TIMER {
    private $StartTime = 0;
    private $StopTime = 0;
    function get_microtime() {
        list($usec, $sec) = explode(' ', microtime());
        return ((float)$usec+(float)$sec);
    }
    function start() {
        $this->StartTime = $this->get_microtime();
    }
    function stop() {
        $this->StopTime = $this->get_microtime();
    }
    function spent() {
        return '<br>' . ($this->StopTime - $this->StartTime) . '(s)<br>';
    }
}


/*
 * 负责向处理与用户相关的事务
 *
 */
class USER {
    /*
     * 检查两次访问间隔是否过短
     * 
     */
    static function valid() {
        $cache = new CACHE();
        $prevent_repeat_key = sprintf( CACHE_REPEAT_DELAY_KEY, $_SERVER['REMOTE_ADDR'] );
        if( $cache->get($prevent_repeat_key) ) {
            return false;
        } else {
            $cache->set($prevent_repeat_key, 1, 0, CACHE_REPEAT_DELAY_TIME);
            return true;
        }
    }

    /*
     * 正常返回值
     * @返回值类似 {"status":"OK","value":"-1","from":"cache"}
     * 
     */
    static function send( $value, $extra = null ) {
        return USER::_send_( 'OK', $value, $extra );
    }

    /*
     * 返回错误提示
     * @返回值类似 {"status":"ERROR","value":"-10"}
     * 
     */
    static function error( $value, $extra = null ) {
        return USER::_send_( 'ERROR', $value, $extra );
    }

    /*
     * 走你
     * 
     */
    static function _send_( $status, $value, $extra = null ) {
        # 包装基本参数
        $return = array(
            'status' => $status,
            'value'  => $value,
        );

        # 如果有额外参数就带上
        if( $extra ) 
            $return = array_merge($return, $extra);

        # 搞成json送出
        echo json_encode($return);

        return true;
    }
}