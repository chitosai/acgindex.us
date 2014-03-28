    <div class="nav">
        <? 
        $current = $_REQUEST['method'];
        foreach( $nav as $url => $name ): 
        ?>
        <div class="nav-item<? if( $current == $url ) echo ' nav-current-item'; ?>">
            <a href="/<?=$url;?>"><?=$name;?></a>
        </div>
        <? endforeach; ?>
    </div>
