<? if( !defined('BASE_PATH') ) exit(); ?>
<!doctype html>
<html lang="cn">
<head>
    <? include TEMPLATE_COMMON_PATH . 'head.php'; ?>
</head>
<body>
    <? include TEMPLATE_COMMON_PATH . 'header.php'; ?>
    <div class="content">
        <div>
            <form action="/addresource">
                <h1>合集<small><?=$message;?></small></h1>
                <div>
                    <label>
                        <span>Entry Id:</span>
                        <input type="text" name="eid">
                    </label>
                </div>
                <div>
                    <label>
                       <span>Bili Av:</span>
                       <input type="text" name="bili">
                   </label>
                </div>
                <div>
                    <span>&nbsp;</span>
                    <input type="submit" class="submit" value="提交">
                </div>
            </form>
        </div>
    </div>
    <? include TEMPLATE_COMMON_PATH . 'footer.php'; ?>
</body>
</html>