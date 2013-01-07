<?php
    class Solarsteinn {
        const default_output_template = '%e %B, *',
            today = true,
            yesterday = true,
            relative_6h = true, 
            downcase_output = true,
            lang = 'ru_RU',
            use_utf = true,
            steinn_mark = "*";

        public static $initialized = false,
            $default_time_zones = array(
                '@night' => 'ночью',
                '@sunrise' => 'на рассвете',
                '@morning' => 'утром',
                '@day' => 'днем',
                '@sunset' => 'на закате',

                '0:00..5:00' => '@night',
                '0:00..0:30' => 'полночь',
                '6:00...10:00' => '@morning',
                '10:00...12:00' => 'до полудня',
                '12:00...@sunset/18:00' => '@day',
                '@sunset/18:00..22:30' => 'вечером',
                '@sunset..@sunset+0:30' => 'на закате',
                '22:30...0:00' => 'до полуночи'
            ),
            $date_consts = array(
                'now' => 'только что',
                'today' => 'сегодня',
                'yesterday' => 'вчера',
                'seconds' => array('секунда', 'секунды', 'секунд'),
                'minutes' => array('минута', 'минуты', 'минут'),
                'hours' => array('час', 'часа', 'часов')
            ); 

        public function __construct($default_zones = false) {
            if (is_array($default_zones))
                self::$default_time_zones = $default_zones;
            if (self::use_utf) mb_internal_encoding('UTF-8');
            setlocale(LC_TIME, (self::lang != '' ? self::lang : 'en_EN').(self::use_utf ? '.UTF-8' : ''));

            self::$initialized = true;
        }

        public static function compile ($time, $data = false, $outputTemplate = self::default_output_template) {
            if(!self::$initialized)
                new self;

            if (is_bool($data) and !$data) {
                if (is_array(self::$default_time_zones) and !empty(self::$default_time_zones))
                    $data = self::$default_time_zones;  else return false;
            }

            if ($date = strtotime($time)) {
                $compiled_date = strftime($outputTemplate, $date);
                return self::parse_steinn($compiled_date, self::get_relative_time(time(), "назад"));
            } else return false;
        }

        private static function parse_steinn ($str, $value = '') {
            $str = str_replace(self::steinn_mark, $value, $str);
            return (self::downcase_output ? mb_strtolower($str) : $str);
        }

        private static function get_relative_time ($time, $end_word = '', $compile_diff = false) {
            $diff = $origin_diff = time() - strtotime($time);
            if ($diff == 0) return self::$date_consts['now'];
            if ($diff < 60)
                return trim(self::decline($diff, self::$date_consts['seconds']).' '.$end_word);
            $diff = round($diff / 60);
            if ($diff < 60)
                return trim(self::decline($diff, self::$date_consts['minutes']).' '.$end_word);
            $diff = round($diff / 60);
            if ($diff < 24)
                return trim(self::decline($diff, self::$date_consts['hours']).' '.$end_word);
            return $compile_diff ? $origin_diff : false;
        }

        private static function decline ($number, $variants, $onlyword = false) {
            if (!is_array($variants)) $variants = array_filter(explode(' ', $variants));
            if (empty($variants[2])) $variants[2] = $variants[1];
            
            $digit = preg_replace('/[^0-9]+/s', '', $number) % 100;
            if ($onlyword) $number = '';
            
            if ($digit >= 5 && $digit <= 20) 
                $declined_str = $number.' '.$variants[2];
            else {
                $digit %= 10;
                if ($digit == 1) 
                    $declined_str = $number.' '.$variants[0];
                elseif ($digit >= 2 && $digit <= 4) 
                    $declined_str = $number.' '.$variants[1];
                else 
                    $declined_str = $number.' '.$variants[2];
            }

            return trim($declined_str);
        }

         private static function parse_date ($string) {
            
         }
    }