<?

class Frontend {
    /**
     * 首页
     */
    static public function index() {
        self::render('index');
    }

    /**
     * 渲染前台
     */
    static public function render($template, $data = array()) {
        global $FRONTEND_NAV;
        $data['type'] = 'frontend';
        $data['nav']  = $FRONTEND_NAV;
        Core::render($template, $data);
    }
}