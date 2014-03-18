<!doctype html>
<html lang="cn">
<head>
    <? include '_head.html'; ?>
</head>
<body>
    <? include '_header.html'; ?>

    <div class="content">
        <h1>总访问量</h1>
        <p><?=$total_view;?></p>
        <h1>最近一周访问量</h1>
        <p><?=$weekly_view;?></p>
        <h1>昨日访问量</h1>
        <p><?=$lastday_view;?></p>
        <h1>今日访问量</h1>
        <p><?=$today_view;?></p>
    </div>
    
    <? include '_footer.html'; ?>
</body>
</html>