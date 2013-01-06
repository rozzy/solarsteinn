<?php
	class Solarsteinn {
		const defaultOutputTemplate = 'j F, *';

		public function __construct() {
			
		}

		public static function compile ($time, $data, $outputTemplate = self::defaultOutputTemplate) {
			echo $outputTemplate;
		}
	}