    <div class="nav">
        <? 
        $current = $_REQUEST['method'];
        global $BACKEND_NAV;
        foreach( $BACKEND_NAV as $url => $name ): 
        ?>
        <div class="nav-item<? if( $current == $url ) echo ' nav-current-item'; ?>">
            <a href="/<?=$url;?>"><?=$name;?></a>
        </div>
        <? endforeach; ?>
    </div>
