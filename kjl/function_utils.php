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
        $result[$key] = $post->ID;
    }
    return $result;
}

function get_current_time() {
    return time();
}

function cal_first_day_timestamp($cur_ts) {
    $cur_ts += 3600 * 8; // for considering time zone
    $seconds_one_day = 3600 * 24;
    $cur_ts -= $cur_ts % $seconds_one_day;
    $week_day = date('w', $cur_ts);
    if ($week_day == 0) {
        $week_day += 7;
    }
    $cur_ts -= 3600 * 8; // for considering time zone
    return $cur_ts - ($week_day-1) * $seconds_one_day;
}

function cal_last_day_timestamp($cur_ts) {
    $seconds_one_day = 3600 * 24;
    return cal_first_day_timestamp($cur_ts) + 7 * $seconds_one_day;
}

function parse_date_string($date_str) {
    $time_stamp = $date_str; //strtotime($date_str);
    $time_stamp += 3600 * 8;
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

function import_one_post($content, $post_id) {
    echo 'Post Title: '.$content['title'];
    //print_r($content);

    $my_post = array(
        //'post_name' => 'name?',
        'post_title' => $content['title'],
        'post_content' => $content['content'],
        'post_status' => 'publish',
        //'post_category' => array(3),
        //'tags_input' => '测试, 自动',
    );

    // deal with categories
    if (isset($content['category'])) {
        $cat_ids = array_values(get_category_ids($content['category']));
        if (count($cat_ids) > 0) {
            $my_post['post_category'] = $cat_ids;
        }
    }
    // deal with tags
    //if (isset($content['']))

    if ($post_id >= 0) {
        $my_post['ID'] = $post_id;
        echo "\n\tupdating ...\t post_id = ". $post_id, "\n";
        wp_update_post($my_post);
    } else {
        echo "\n\tinserting ...\t post_id = ". $post_id, "\n";
        $post_id = wp_insert_post($my_post);
    }

    $post_metas = $content;
    unset($post_metas['title']);
    unset($post_metas['content']);
    if (isset($post_metas['category'])) {
        unset($post_metas['category']);
    }
    foreach($post_metas as $key => $value) {
        update_post_meta($post_id, $key, $value);
    }
}

function get_share_content() {
    $max_allowed_total_length = 129;
    $max_allowed_title_length = 20;
    $max_allowed_address_length = 20;
    $PREFIX_STR = '【#开讲喽#(www.kaijianglou.cn)】';
    $LINE_BREAK = '\n';
    $post_id = get_the_ID();
    $post = get_post($post_id);
    $title_str = the_title('', '', false);
    if (get_str_count($title_str) > $max_allowed_title_length) {
        $title_str = cut_str($title_str, $max_allowed_title_length-2, 0);
    }
    $title_str = $PREFIX_STR . $title_str;
    $address_str = '地点：'.get_post_meta($post_id, 'address', true);
    if (get_str_count($address_str) > $max_allowed_address_length) {
        $address_str = cut_str($address_str, $max_allowed_address_length-2, 0);
    }
    $time_str = '时间：'.get_start_time_show_string().' - '.get_end_time_show_string();
    $total_str = $title_str;
    $remaining_length = $max_allowed_total_length - get_str_count($title_str)
        - get_str_count($time_str) - get_str_count($address_str) - 2;
    if ($remaining_length > 10) {
        $content_str = strip_tags(apply_filters('the_content',$post->post_content));
        $content_str = preg_replace("/\s(?=\s)/", "\\1", $content_str);
        $content_str = str_replace("\n", ' ', $content_str);
        $content_str = cut_str($content_str, $remaining_length-2);
        $total_str .= $LINE_BREAK.$content_str;
    }
    $total_str .= $LINE_BREAK.$time_str;
    $total_str .= $LINE_BREAK.$address_str;
    //$total_str .= "\n".$SUFFIX_STR;
    return $total_str;
}

function get_str_count($string) {
    $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
    preg_match_all($pa, $string, $t_string);
    $total_count = 0;
    foreach($t_string as $arr) {
        $total_count += count($arr);
    }

    // One english character is only considered to have length 1/2.
    $en_pa = "/[\d\sA-Za-z[:punct:]]+/u";
    preg_match_all($en_pa, $string, $t_string);
    $en_count = 0;
    foreach($t_string as $arr) {
        foreach($arr as $str) {
            $en_count += strlen($str);
        }
    }
    $total_count -= floor($en_count / 2.0);
    return $total_count;
}

function get_category_ids($category_names, $separator=',') {
    $name_array = explode($separator, $category_names);
    $result = array();
    foreach($name_array as $name) {
        $name = trim($name);
        $cat_id = get_cat_ID($name);
        if ($cat_id <= 0) { // the category doesn't exist yet
            $cat_id = create_category($name);
        }
        $result[$name] = $cat_id;
    }
    return $result;
}

function create_category($cat_name, $parent=0) {
    $args = array('category_parent' => $parent);
    $new_cat_id = wp_insert_term($cat_name, "category", $args);
    return $new_cat_id['term_id'];
}

function delete_all_category_ids() {
    $category_ids = get_all_category_ids();
    foreach($category_ids as $cat_id) {
        wp_delete_category($cat_id);
    }
}

/*function delete_all_tags() {
    $category_ids = get_all_category_ids();
    foreach($category_ids as $cat_id) {
        wp_delete_category($cat_id);
    }
}*/
