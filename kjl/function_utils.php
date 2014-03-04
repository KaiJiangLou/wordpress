<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 14-3-4
 * Time: 21:11
 */

//date_default_timezone_set('Asia/Shanghai');

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
    $time_stamp = strtotime($date_str);
    $format = "Y年m月d日";
    if ($time_stamp % 3600 !== 0) {
        $format .= " H:i";
    }
    return date($format, $time_stamp);
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