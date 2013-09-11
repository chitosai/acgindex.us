<?php
require_once('accounts.php');
require_once('config.php');
require_once('MySQL.php');
require_once('utility.php');

/*
 * 数据中心负责查询收
 * @ 找到资源的情况下返回资源地址
 * @ 没找到的情况下返回-1
 */
class GET {
    # 缓存KEY
    static private $cache_key = 'CACHE_KEY';

    /*
     * 设置缓存KEY的值
     * 
     */
    static function set_cache_key( $key ) {
        self::$cache_key = $key;
    }

    /*
     * 在缓存中查找
     * 
     */
    static function cache( $bgmid, $epid, $source ) {
        $cache = new CACHE();
        $r = $cache->get( self::$cache_key );
        unset($cache);
        return $r;
    }

    /*
     * 从数据库中查找并缓存
     * 
     */
    static function db( $bgmid, $epid, $source ) {
        # 发起查询
        $db = new mysql();
        $data = $db->query(sprintf("SELECT `ep`.`%s` FROM `ep` LEFT JOIN `entry` ON `ep`.`eid` = `entry`.`id` AND `ep`.`epid` = %s WHERE `entry`.`bgm` = %s", $source, $epid, $bgmid));

        # 没有资源的情况
        if( !$data ) $r = '-1';
        # 找到资源的情况
        else $r = $data[0]['ep']['bili'];

        # 缓存
        $cache = new CACHE();
        if( $r != '' || $r != '-1')
            $cache->set( self::$cache_key, $r, 0, CACHE_EXIST_EXPIRE );
        else
            $cache->set( self::$cache_key, '-1', 0, CACHE_NOT_EXIST_EXPIRE );
        unset($cache);

        return $r;
    }

    /*
     * 抓取BT资源（目前是KTXP）并缓存
     * @ 找到资源的情况下返回资源地址
     * @ 没找到的情况下返回-1
     */
    static function bt( $bgmid, $epid ) {
        # 根据bgmid取中文名
        $db = new mysql();

        # 先从别名表里查找真名
        $tmp = $db->query(sprintf('SELECT `entry`.`id`, `entry`.`name_cn`, `names`.`real_name` FROM `entry` LEFT JOIN `names` ON `entry`.`id` = `names`.`eid` AND `names`.`source` = %s WHERE `entry`.`bgm` = %s', CACHE_BT_SOURCE_ID, $bgmid));
        # 反正要先查entry表的，就顺便把name_cn和eid取出来好了，如果要调用bt_first_query就不用再查一次了
        $real_name = $tmp[0]['names']['real_name'];
        $name_cn = $tmp[0]['entry']['name_cn'];
        $eid = $tmp[0]['entry']['id'];
        
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
            $r = self::bt_first_query( $bgmid, $epid, $eid, $name_cn );
        }

        # 本次查询结果写入缓存
        $cache = new CACHE();

        # 找到结果
        if( $r && $r != '-1' ) {
            $cache->set( self::$cache_key, $r, 0, CACHE_BT_EXIST_EXPIRE );
        } else {
        # 没有结果
            $cache->set( self::$cache_key, '-1', 0, CACHE_BT_EXIST_EXPIRE );
        }

        unset($cache);
        return $r;
    }

    /*
     * 第一次查找某资源，从TAGS表中取所有别名，依次遍历
     * 
     */
    static function bt_first_query( $bgmid, $epid, $eid, $name_cn ) {
        $names = array();
        array_push($names, $name_cn);
        # 取出TAG表中保存的所有别名
        $db = new mysql();
        $tags = $db->query(sprintf('SELECT `tags`.`name` FROM `tags` RIGHT JOIN `link` ON `tags`.`tid` = `link`.`tid` WHERE `link`.`eid` = %s', $eid));
        foreach( $tags as $tag ) {
            array_push($names, $tag['tags']['name']);
        }

        # 真名
        $found_name = '-1';

        # 发起搜索
        foreach($names as $name) {
            $r = self::bt_query( $name, $epid );
            # 找到资源的话就结束遍历
            if( $r ) {
                $found_name = $name;
                break;
            }
        }

        # 保存真名
        $db->insert('names', array(
            'source'     => CACHE_BT_SOURCE_ID,
            'eid'        => $eid,
            'index_name' => '',
            'real_name'  => $found_name
        ));

        if( $r ) 
            return $r;
        else
            return '-1';
    }

    /*
     * 发起BT搜索
     * 
     */
    static function bt_query( $name, $epid ) {
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
            return false;
    }
}