<?

function autoload($class) {
    global $autoloads;
    
    foreach( $autoloads as $dict ) { 
        $file = BASE_PATH . '/' . $dict . '/' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($file)) {
            require $file;
            return true;
        }
    }
}

spl_autoload_register('autoload');