<?php

class Data {
    # 缓存KEY
    static private $cache_key = null;

    /**
     * 对外接口
     * 
     */
    static function Get($eid, $epid, $source) {
        # 检查缓存
        $value = self::cache($eid, $epid, $source);

        # 没有缓存值
        if( !$value ) {
            # BT资源只储存在缓存中，如果缓存里没有数据就重新抓
            if( $source == 'bt' ) {
                $value = self::bt($eid, $epid);
            }
            # 其他资源去查数据库
            else {
                $value = self::db($eid, $epid, $source);
            }
        }

        LOG::add($eid, $epid, $source, $value);
        return $value;
    }

    /*
     * 在缓存中查找
     * 
     */
    static function cache($eid, $epid, $source) {
        # 检查$cache_key，不存在的话就生成
        if( !self::$cache_key ) 
            self::$cache_key = sprintf(CACHE_KEY, $eid, $epid, $source);
        return CACHE::get(self::$cache_key);
    }

    /*
     * 从数据库中查找并缓存
     * 
     */
    static function db($eid, $epid, $source) {
        # 发起查询
        $data = DB::query("SELECT `ep`.`{$source}` FROM `ep` LEFT JOIN `entry` ON `ep`.`eid` = `entry`.`id` AND `ep`.`epid` = ? WHERE `entry`.`id` = ?", array($epid, $eid));

        # 没有资源的情况
        if( !count($data) ) $r = '-1';
        # 找到资源的情况
        else $r = $data[0]['bili'];

        # 缓存
        if( $r != '' || $r != '-1')
            CACHE::set(self::$cache_key, $r, CACHE_EXIST_EXPIRE);
        else
            CACHE::set(self::$cache_key, '-1', CACHE_NOT_EXIST_EXPIRE);

        return $r;
    }

    /*
     * 抓取BT资源（目前是KTXP）并缓存
     * @ 找到资源的情况下返回资源地址
     * @ 没找到的情况下返回-1
     */
    private static function bt( $eid, $epid ) {
        # 根据eid取中文名
        # 先从别名表里查找真名
        $tmp = DB::query('SELECT `entry`.`name_cn`, `entry`.`cid`, `names`.`real_name` FROM `entry` LEFT JOIN `names` ON `entry`.`id` = `names`.`eid` AND `names`.`source` = ? WHERE `entry`.`id` = ?', array(CORE::get_source_id('bt'), $eid));

        # 验证分类，现在只有动画有BT资源
        if( $tmp[0]['cid'] != 2 ) {
            return -1;
        }

        # 反正要先查entry表的，就顺便把name_cn和eid取出来好了，如果要调用bt_first_query就不用再查一次了
        $real_name = $tmp[0]['real_name'];
        $name_cn = $tmp[0]['name_cn'];
        
        # 如果取到真名且不为-1，说明是正确的极影使用名
        if( $real_name && $real_name != '-1' ) {
            $r = self::bt_query( $real_name, $epid );
        }
        # 如果取到的真名为-1，表示以目前收录的所有names都无法在极影搜到资源
        # 这时直接返回-1给用户即可
        else if( $real_name == '-1' ) {
            $r = '-1';
        }
        # 如果没有取到值，说明这是第一次有用户请求这部作品的BT资源
        else if( !$real_name ) {
            $r = self::bt_first_query( $eid, $epid, $eid, $name_cn );
        }

        # 本次查询结果写入缓存
        # 找到结果
        if( $r && $r != '-1' ) {
            CACHE::set( self::$cache_key, $r, CACHE_BT_EXIST_EXPIRE );
        } else {
        # 没有结果
            CACHE::set( self::$cache_key, '-1', CACHE_BT_EXIST_EXPIRE );
        }
        return $r;
    }

    /*
     * 发起BT搜索
     * 
     */
    private static function bt_query( $name, $epid ) {
        # 准备发送请求
        if( $epid < 10 ) $str_epid = '0' . $epid;
        else $str_epid = $epid;
        $url = sprintf(KTXP_SEARCH , urlencode( $name . ' ' . $str_epid ));

        # 获得返回的页面
        $html = file_get_contents($url);

        # 查找第一个下载链接，就是种子数最多的资源了
        $find = preg_match("/<a href=\"\/down\/[\w\d\/]+\.torrent\" class=\"quick-down cmbg\"><\/a><a href=\"([\w\d\/\.]+)\" target=\"_blank\">/", $html, $match);

        if( $find )
            return $match[1];
        else
            return '-1';
    }

    /*
     * 第一次查找某资源，从TAGS表中取所有别名，依次遍历
     * 
     */
    private static function bt_first_query( $eid, $epid, $eid, $name_cn ) {
        $names = array();
        array_push($names, $name_cn);

        # 取出TAG表中保存的所有别名
        $tags = DB::query('SELECT `tags`.`name` FROM `tags` RIGHT JOIN `link` ON `tags`.`tid` = `link`.`tid` WHERE `link`.`eid` = ?', array($eid));
        foreach( $tags as $tag ) {
            array_push($names, $tag['name']);
        }

        # 真名
        $found_name = '-1';

        # 发起搜索
        foreach($names as $name) {
            $r = self::bt_query( $name, $epid );
            # 找到资源的话就结束遍历
            if( $r != '-1' ) {
                $found_name = $name;
                break;
            }
        }

        # 保存真名
        DB::insert('names', array(
            'source'     => CORE::get_source_id('bt'),
            'eid'        => $eid,
            'index_name' => '',
            'real_name'  => $found_name
        ));

        if( $r ) 
            return $r;
        else
            return '-1';
    }
}