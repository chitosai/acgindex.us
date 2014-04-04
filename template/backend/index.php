<? if( !defined('BASE_PATH') ) exit(); ?>
<!doctype html>
<html lang="cn">
<head>
    <? include TEMPLATE_COMMON_PATH . 'head.php'; ?>
</head>
<body>
    <? include TEMPLATE_COMMON_PATH . 'header.php'; ?>

    <div class="content">
        <h1>总访问量</h1>
        <p><?=$total_view;?> ( <small><?=$total_avg;?>/d</small> )</p>
        <h1>最近一周访问量</h1>
        <p><?=$weekly_view;?> ( <small><?=$weekly_avg;?>/d</small> )</p>
        <h1>昨日访问量</h1>
        <p><?=$lastday_view;?></p>
        <h1>今日访问量</h1>
        <p><?=$today_view;?></p>
    </div>
    
    <? include TEMPLATE_COMMON_PATH . 'footer.php'; ?>
</body>
</html>