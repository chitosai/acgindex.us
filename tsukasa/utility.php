<?php

include('accounts.php');
include('config.php');

function utility_error($title, $detail = '') {
	echo $title . ' <br><br>' . $detail;
	exit();
}

// 
// memcache操作类
// 
class MC { 
    private $mmc = null; 
    function __construct(){ 
        $this->mmc = new memcache(); 
        $this->mmc->addServer( MC_HOST, MC_PORT );
    } 
    function set($key, $var, $compress = MEMCACHE_COMPRESSED, $expire = 3600){ 
        if(!$this->mmc) return false; 
        return $this->mmc->set($key, $var, $compress, $expire); 
    } 
    function get($key){ 
        if(!$this->mmc) return false; 
        return $this->mmc->get($key); 
    } 
    function incr($key, $value=1){ 
        if(!$this->mmc) return false; 
        return $this->mmc->increment($key, $value); 
    } 
    function decr($key, $value=1){ 
        if(!$this->mmc) return false; 
        return $this->mmc->decrement($key, $value); 
    } 
    function delete($key){ 
        if(!$this->mmc) return false; 
        return $this->mmc->delete($key); 
    }
    function flush(){
        if(!$this->mmc) return false; 
        return $this->mmc->flush();
    }
}