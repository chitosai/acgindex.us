<?php

require_once('accounts.php');
require_once('config.php');

/* 
 * memcache操作类
 *
 */
class MC { 
    private $mmc = null; 
    function __construct() { 
        $this->mmc = new memcache(); 
        $ret = $this->mmc->connect( MC_HOST, MC_PORT );
        if( !$ret ) die('Memcache init failed');
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