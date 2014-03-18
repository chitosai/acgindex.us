<?

class Core {
    /**
     * 检查用户请求是否合法
     * @param [integer] $eid    [entry id]
     * @param [integer] $epid   [ep id]
     * @param [string]  $source [bili/bt/etc..]
     */
    static function valid($eid, $epid, $source) {
        # 检查访问是否过于频繁
        $prevent_repeat_key = sprintf(CACHE_REPEAT_DELAY_KEY, $_SERVER['REMOTE_ADDR']);
        if( Cache::has($prevent_repeat_key) ) {
            return '请求速度过快';
        } else {
            Cache::set($prevent_repeat_key, 1, CACHE_REPEAT_DELAY_TIME);
        }

        # 验证参数
        global $SOURCE_LIST;
        $eid = intval($eid);
        $epid = floatval($epid);

        if( !$eid || !$epid || !in_array($source, $SOURCE_LIST) ) {
            return '请求的参数非法';
        }

        # NO PROBLEM
        return true;
    }

    /**
     * 检查用户是否登录
     * @return true/false
     */
    static function auth() {
        if( !isset($_COOKIE['hash']) || !isset($_COOKIE['timestamp']) )
            return Core::r401();

        $hash = $_COOKIE['hash'];
        $time = $_COOKIE['timestamp'];

        // 确定cookie有效期
        if( (time() - $time) > ADMIN_COOKIE_EXPIRE )
            return Core::r401('登录状态已过期');

        // 确认auth是否合法
        $_hash = hash('sha256', ADMIN_PASS . '^^^' . $time);

        if( $hash !== $_hash )
            return Core::r401('密码错误');

        // 没问题了
        return true;
    }

    /**
     * 把$source字符串转为$source id
     * @param [string] $source [bili/bt/etc..]
     */
    static function get_source_id($source) {
        global $SOURCE_LIST;
        return $SOURCE_LIST[$source];
    }

    /**
     * 渲染
     */
    static function render($template, $data = array()) {
        extract($data);
        include TEMPLATE_PATH . '/' . $template . '.php';
        return true;
    }

    /**
     * 404
     */
    static function r404($message = '目录娘检查了你输入的地址，但是什么也没发现') {
        # 模板自己会发出Status: 404
        self::render('404');
        exit();
    }

    /**
     * 401
     */
    static function r401($message = '你是猴子请来的邓璐吗？', $redirect_url = '') {
        header('Status: 401 - Unauthorized');
        self::render('error', array(
            'title'        => '401 请登录', 
            'message'      => $message,
            'redirect_url' => $_SERVER['REQUEST_URI'],
            'show_login'   => true
        ));
        exit();
    }
}