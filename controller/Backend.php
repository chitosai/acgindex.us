<?

class Backend {
    /**
     * 虽然叫首页但其实都是统计数据
     */
    static function Index() {
        $data = array();

        # 总点击量
        $r = DB::query('SELECT COUNT(*) FROM `log`');
        $data['total_view'] = intval($r[0]['COUNT(*)']);

        # 周点击量
        $r = DB::query('SELECT COUNT(*) FROM `log` WHERE DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(`timestamp`)');
        $data['weekly_view'] = intval($r[0]['COUNT(*)']);

        # 昨日点击
        $r = DB::query('SELECT COUNT(*) FROM `log` WHERE CURDATE() - date(`timestamp`) = 1');
        $data['lastday_view'] = intval($r[0]['COUNT(*)']);

        # 今日
        $r = DB::query('SELECT COUNT(*) FROM `log` WHERE CURDATE() = date(`timestamp`)');
        $data['today_view'] = intval($r[0]['COUNT(*)']);
        
        self::render('index', $data);
    }

    /**
     * 后台Python端的抓取LOG
     */
    static function Log() {
        $data = array('logs' => array());

        for( $delta = 0; $delta <= 5; $delta++ ) {
            // filename
            $date = new DateTime();
            $date->modify('-' . $delta . ' day');
            $date = $date->format('Y-m-d');
            $filename = LOG_PATH . '/' . $date . '.log';

            // open it
            if( file_exists($filename) ) {
                $file = file_get_contents( $filename );

                // 去掉每次运行的开始/结束提示
                $file = preg_replace('/\[\d{2}:\d{2}:\d{2}\] (?:update bili resources|END)[\r\n]+/', '', $file);
                $file = preg_replace('/=+[\r\n]+/', '', $file);

                // 高亮
                $file = preg_replace('/(\[\d{2}:\d{2}:\d{2}\] \[NO ENTRY\]) name: ([^\r\n]+)/', 
                                     '<b>$1</b> name: <span class="easy-select">$2</span>', $file);
                $file = preg_replace('/(\[\d{2}:\d{2}:\d{2}\] \[NO EP\]) id: (\d+) \| bid: (\d+) \| name: ([^\r\n]+)/', 
                                     '<b>$1</b> id: <span class="easy-select">$2</span> | bid: <span class="easy-select">$3</span> | name: <span class="easy-select">$4</span>', $file);
                // 低亮
                $file = preg_replace('/(\[\d{2}:\d{2}:\d{2}\] \[skip\] [^\r\n]+)/', '<span class="less-important">$1</span>', $file);

                // 换行符替换为<br>
                $file = preg_replace('/[\r\n]+/', '<br>', $file);

                $data['logs'][$date] = $file;
            } else {
                $data['logs'][$date] = "{$filename} 不存在";
            }
        }

        self::render('log', $data);
    }

    /**
     * 手动关联资源
     */
    static function AddResource() {
        extract($_REQUEST);
        $data = array('message' => '');

        # 检查输入
        if( isset($eid) && isset($bili) ) {
            $eid = intval($eid);
            $bili = intval($bili);
            
            if( $eid > 0 && $bili > 0 ) {
                # 开始插入数据
                # 检查总话数
                $r = DB::query('SELECT `total` FROM `entry` WHERE `id` = ?', array($eid));
                if( $r ) {
                    $total = $r[0]['total'];
                }

                # 写入条目数据
                for( $i = 1; $i <= $total; $i++ ) {
                    DB::update('ep', array(
                        'bili' => "{$bili}/index_{$i}.html"
                    ), array(
                        'eid'  => $eid,
                        'epid' => $i
                    ));
                }

                $data['message'] = '更新完毕';
            }
        }

        # 
        self::render('addresource', $data);
    }

    /**
     * 渲染后台页面
     */
    static function render($template, $data = array()) {
        global $BACKEND_NAV;
        $data['type'] = 'backend';
        $data['nav']  = $BACKEND_NAV;
        Core::render('backend/' . $template, $data);
    }
}