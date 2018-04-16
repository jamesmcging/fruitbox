<?php
require '.env';

class DB {

	private static $sDBName = 'fruitbox';
	private static $instance = NULL;

	/**
	 * Return DB instance or create intitial connection
	 * @return object (PDO)
	 * @access public
	 */
	public static function getInstance() {
		if (!self::$instance)
		{
        self::$instance = new PDO(DB_CONNECTION_STRING, DB_USER, DB_PASSWORD);
        self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$instance->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
		}

		return self::$instance;
	}

	private function __construct() {}

  private function __clone() {}

}
