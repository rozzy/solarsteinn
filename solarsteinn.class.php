<?php
	class Solarsteinn {
		const default_output_template = 'j F, *';
		const downcase_output = true;
		const lang = 'ru_RU';
		const use_utf = true;

		public function __construct() {
			if (self::use_utf) mb_internal_encoding('UTF-8');
			setlocale(LC_ALL, (isset(self::lang) && self::lang != '' ? self::lang : 'en_EN').(self::use_utf ? '.UTF-8' : '')); 
		}

		public static function compile ($time, $data, $outputTemplate = self::default_output_template) {
			echo strftime($outputTemplate,self::parse_date($time));
		}

		private static function parse_date ($string) {
			return strtotime($string);
		}
	}