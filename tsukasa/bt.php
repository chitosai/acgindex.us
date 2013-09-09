<?php
require_once('accounts.php');
require_once('config.php');
require_once('MySQL.php');
require_once('utility.php');

/*
 * 处理BT（目前是KTXP）资源
 * 
 */
class BT {
    /*
     * 抓取BT资源并缓存
     * @ 找到资源的情况下返回资源地址
     * @ 没找到的情况下返回-1
     */
    static public function update( $bgmid, $epid ) {
        # 根据bgmid取中文名
        $db = new mysql();
        # 查找bgm收录的中文名及names表收录的别名
        $names = $db->query(sprintf('SELECT `entry`.`name_cn`, `names`.`real_name` FROM `entry` LEFT JOIN `names` ON `entry`.`id` = `names`.`eid` AND `names`.`source` = %s WHERE `entry`.`bgm` = %s', MC_BT_SOURCE_ID, $bgmid));
        if( !$names ) return -1;
        # 如果有别名则选用别名，没有别名就直接用bgm的中文名
        $names = $names[0];
        if( $names['names']['real_name'] )
            $name = $names['names']['real_name'];
        else
            $name = $names['entry']['name_cn'];

        # 准备发送请求
        if( $epid < 10 ) $str_epid = '0' . $epid;
        else $str_epid = $epid;
        $url = sprintf(KTXP_SEARCH , urlencode( $name . ' ' . $str_epid ));

        # 获得返回的页面
        $html = file_get_contents($url);

        # 查找第一个下载链接，就是种子数最多的资源了
        $find = preg_match("/<a href=\"\/down\/[\w\d\/]+\.torrent\" class=\"quick-down cmbg\"><\/a><a href=\"([\w\d\/\.]+)\" target=\"_blank\">/", $html, $match);

        # 准备写入缓存
        $mc = new MC();
        $mc_key = sprintf(MC_KEY, $bgmid, $epid, 'bt');

        # 找到结果
        if( $find ) {
            $ret = $mc->set( $mc_key, $match[1], 0, MC_BT_EXIST_EXPIRE );
            return $match[1];
        } else {
        # 没有结果
            $mc->set( $mc_key, '-1', 0, MC_BT_EXIST_EXPIRE );
            return -1;
        }
    }
}