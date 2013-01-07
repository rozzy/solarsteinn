<?php
	class Solarsteinn {
		const default_output_template = '%e %B, *',
			downcase_output = true,
			lang = 'ru_RU',
			use_utf = true,
			steinn_mark = "*";

		private static $default_time_zones = array(); 

		public function __construct($default_zones = false) {
			if (is_array($default_zones))
				self::$default_time_zones = $default_zones;
			if (self::use_utf) mb_internal_encoding('UTF-8');
			setlocale(LC_TIME, (self::lang != '' ? self::lang : 'en_EN').(self::use_utf ? '.UTF-8' : ''));
		}

		public static function compile ($time, $data = false, $outputTemplate = self::default_output_template) {
			if (is_bool($data) and !$data) {
				if (is_array(self::$default_time_zones) and !empty(self::$default_time_zones))
					$data = self::$default_time_zones;  else return false;
			}

			if ($date = strtotime($time)) {
				$compiled_date = strftime($outputTemplate, $date);
				echo self::parse_steinn($compiled_date, 'утром');
			} else return false;
		}

		private static function parse_steinn ($str, $value = '') {
			$str = str_replace(self::steinn_mark, $value, $str);
			return (self::downcase_output ? mb_strtolower($str) : $str);
		}

		// private static function parse_date ($string) {
		// 	return strtotime($string);
		// }
	}