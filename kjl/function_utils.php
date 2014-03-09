<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 14-3-4
 * Time: 21:11
 */

//date_default_timezone_set('Asia/Shanghai');

function get_all_post_metadata_by_name($meta_name) {
    $args = array('posts_per_page' => -1);
    $posts_list = get_posts($args);
    $result = array();
    foreach ($posts_list as $post) {
        $key = get_post_meta($post->ID, $meta_name, true);
        $result[$key] = 1;
    }
    return $result;
}

function cal_first_day_timestamp($cur_ts) {
    $cur_ts += 3600 * 8; // for considering time zone
    $seconds_one_day = 3600 * 24;
    $cur_ts -= $cur_ts % $seconds_one_day;
    $week_day = date('w', $cur_ts);
    if ($week_day == 0) {
        $week_day += 7;
    }
    return $cur_ts - ($week_day-1) * $seconds_one_day;
}

function cal_last_day_timestamp($cur_ts) {
    $seconds_one_day = 3600 * 24;
    return cal_first_day_timestamp($cur_ts) + 7 * $seconds_one_day;
}

function parse_date_string($date_str) {
    $time_stamp = $date_str; //strtotime($date_str);
    $format = "Y/m/d";
    $seconds_one_day = 3600 * 24;
    if ($time_stamp % $seconds_one_day !== 0) {
        $format .= " H:i";
    }
    return date($format, $time_stamp);
}

function get_start_time_show_string() {
    $start_time_str = get_post_meta(get_the_ID(), 'start_time', true);
    return parse_date_string($start_time_str);
}

/**
 * @param $start_time_str string date string with format "2014-01-22 10:30"
 * @param $end_time_str string date string with format "2014-01-23 11:30"
 */
function get_end_time_show_string($sec_separator='/') {
    $start_time_str = parse_date_string(get_post_meta(get_the_ID(), 'start_time', true));
    $end_time_str = parse_date_string(get_post_meta(get_the_ID(), 'end_time', true));
    $start_terms = explode(' ', $start_time_str);
    $end_terms = explode(' ', $end_time_str);
    if (count($start_terms) == 2 && count($end_terms) == 2) {
        if (strcmp($start_terms[0], $end_terms[0]) === 0) {
            return $end_terms[1];
        }
        $start_terms = explode($sec_separator, $start_terms[0]);
        $end_terms = explode($sec_separator, $end_terms[0]);
        if (strcmp($start_terms[0], $end_terms[0]) === 0) {
            return substr($end_time_str, 5);
        } else {
            return $end_time_str;
        }
    }
    if (count($end_terms) == 1) {
        if (strstr($end_time_str, ':') === false) { // should be like "2014-01-23"
            if (strcmp(substr($start_time_str, 0, 4), substr($end_time_str, 0, 4)) === 0) {
                return substr($end_time_str, 5);
            }
            else {
                return $end_time_str;
            }
        } else {
            return $end_time_str;
        }
    }
}

function get_common_length($str1, $str2) {
    $max_length_to_try = min(strlen($str1), strlen($str2));
    $cur_length = 1;
    while ($cur_length < $max_length_to_try) {
        if (0 !== strcmp(substr($str1, 0, $cur_length), substr($str2, 0, $cur_length))) {
            break;
        }
        ++$cur_length;
    }
    return $cur_length - 1;
}

function read_json_file($file_name) {
    $content = file_get_contents($file_name);
    $content = json_decode($content, true);
    return $content;
}

function import_one_post($content) {
    echo 'Post Title: '.$content['title'];

    $my_post = array(
        //'post_name' => 'name?',
        'post_title' => $content['title'],
        'post_content' => $content['content'],
        'post_status' => 'publish',
        //'post_category' => array(3),
        'tags_input' => '测试, 自动',
        #'tax_input' => array('address'=>'我的地址', 'start_time'=>'2014/02/29')
    );

    $post_id = wp_insert_post($my_post);
    echo "\n\tinserting ...\t post_id = ". $post_id, "\n";

    $post_metas = $content;
    unset($post_metas['title']);
    unset($post_metas['content']);
    foreach($post_metas as $key => $value) {
        update_post_meta($post_id, $key, $value);
    }
}