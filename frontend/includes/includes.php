<?php
	class Initialisator {
		protected static $start_time = 0;
		public static $settings = array();	
	
		public static function initialise() {
			self::$start_time = microtime(true);
			@set_time_limit(500);
			mb_internal_encoding('UTF-8');
			require_once dirname(__FILE__) . '/DB.php';		
			self::$settings = @parse_ini_file(dirname(__FILE__) . '/../../settings/settings.ini', true);			 
		}
	
		public static function getDB() {
			try {
				return new DB(
					'mysql:dbname=' . self::$settings['mysql']['db']
					. ';host=' . self::$settings['mysql']['host'],
					self::$settings['mysql']['username'],
					self::$settings['mysql']['password'],
					array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')
				);
			}
			catch (Exception $e) {}
		}
	
		public static function getBaseURL() {
			if (isset(self::$settings['general']['baseurl'])) {
				return self::$settings['general']['baseurl'];		
			}
			else return 'http://seime.lt/';
		}
	
		public static function printTime($message) {
			echo " $message: " . round(microtime(true) - self::$start_time, 2). ' seconds<br/>';
			flush();
		}
	}