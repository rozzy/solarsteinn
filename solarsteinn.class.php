<?php
class Solarsteinn {
    const default_output_template = '%e %B, *',
        relative_time = false, 
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
            'hours' => array('час', 'часа', 'часов'),
            'ago' => 'назад'
        ),
        $user_constants = array(),
        $user_timezones = array(),
        $user_timeconsts = array();

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
        if (is_integer($time) and date('c', time()))
            $time = date('c', $time);
        if ($date = strtotime($time)) {
            return self::parse_steinn($date, $data, $outputTemplate);
        } else return false;
    }

    private static function parse_steinn ($date, $data, $outputTemplate) {
        if (time() - $date <= 10) 
            return self::$date_consts['now'];

        $is_today = date("Y-m-d", strtotime("today")) == date("Y-m-d", $date) and self::relative_time;
        $compiled_date = $is_today ? self::steinn_mark :strftime($outputTemplate, $date);

        $relative_time = self::get_relative_time($date, self::$date_consts['ago']);

        $to_compile = $is_today ? (is_bool($relative_time) ? self::parse_zones($date, $data) : $relative_time) : self::parse_zones($date, $data);
        $compiled_date = str_replace(self::steinn_mark, $to_compile, $compiled_date);
        return (self::downcase_output ? mb_strtolower($compiled_date) : $compiled_date);
    }

    private static function parse_zones ($date, $zones) {
        foreach ($zones as $key => $value) {
            if (preg_match('/@([a-zA-Z0-9_]+)$/i', $key, $constants)) {
                unset($zones[$key]);
                self::$user_constants[$constants[1]] = $value;
            }

            if (preg_match('/@([a-zA-Z0-9_]+)$/i', $value, $constants)) {
                if (array_key_exists($constants[1], self::$user_constants))
                    self::$user_timeconsts[$constants[1]] = self::parse_if_mixin($key);
            }
        }

        echo "<pre>";
        print_r($zones);
        print_r(self::$user_constants);
        print_r(self::$user_timeconsts);
        print_r(self::$user_timezones);
    }

    private static function get_relative_time ($time, $end_word = '', $compile_diff = false) {
        if (!self::relative_time) return false;
        if (is_integer($time) and date('c', time()))
            $time = date('c', $time);
        $strtotime = strtotime($time);
        $diff = $origin_diff = time() - $strtotime;
        if (date("Y-m-d", strtotime("yesterday")) == date("Y-m-d", $strtotime)) return self::$date_consts['yesterday'];
        if ($diff < 60)
            return trim(self::decline($diff, self::$date_consts['seconds']).' '.$end_word);
        $diff = round($diff / 60);
        if ($diff < 60)
            return trim(self::decline($diff, self::$date_consts['minutes']).' '.$end_word);
        $diff = round($diff / 60);
        if ($diff <= 6)
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