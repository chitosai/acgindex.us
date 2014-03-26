<?

class DB {
	private static $c = null;

	static function init() {
        try {
            self::$c = new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
        } catch(PDOException $e) {
            API::error('Connect to database failed with message: ' . $e->getMessage());
        }
	}

    static function query($sql, $params = array()) {
        if( !self::$c ) self::init();
        $stmt = self::$c->prepare($sql);
        $stmt->execute($params);
        $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $r;
    }

    static function insert($table, $params) {
        $columns = implode('`,`', array_keys($params));
        $values  = array();
        for( $i = 0; $i < count($params); $i++ ) {
            array_push($values, '?');
        }
        $values = implode(',', $values);
        $sql = "INSERT INTO `{$table}` (`{$columns}`) VALUES({$values})";
        $stmt = self::$c->prepare($sql);
        $stmt->execute(array_values($params));
        $stmt->closeCursor();
        $r = self::$c->lastInsertId();
        return $r;
    }

    static function update($table, $action, $condition) {
        $action_array = array();
        foreach( $action as $key => $value ) {
            array_push($action_array, "`{$key}` = ?");
        }
        $action_sql = implode(' AND ', $action_array);

        $condition_array = array();
        foreach( $condition as $key => $value ) {
            array_push($condition_array, "`{$key}` = '{$value}'");
        }
        $condition_sql = implode(' AND ', $condition_array);

        $sql = "UPDATE `{$table}` SET {$action_sql} WHERE {$condition_sql}";
        $stmt = self::$c->prepare($sql);
        $stmt->execute(array_values($action));
    }
}