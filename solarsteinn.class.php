<?php
	class Solarsteinn {
		const default_output_template = '%e %B, *';
		const downcase_output = true;
		const lang = 'ru_RU';
		const use_utf = true;

		public function __construct() {
			if (self::use_utf) mb_internal_encoding('UTF-8');
			setlocale(LC_ALL, (self::lang != '' ? self::lang : 'en_EN').(self::use_utf ? '.UTF-8' : ''));
		}

		public static function compile ($time, $data, $outputTemplate = self::default_output_template) {
			if ($date = self::parse_date($time)) {
				$compiled_date = strftime($outputTemplate, $date);
				echo $compiled_date;
			} else echo 'Output format error!';
		}

		private static function parse_date ($string) {
			return strtotime($string);
		}
	}