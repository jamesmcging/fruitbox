<?php

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
        self::$instance = new PDO('mysql:host=localhost:13306;dbname=fruitbox','root','password');
        self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$instance->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
		}

		return self::$instance;
	}

	private function __construct() {}

  private function __clone() {}

}
