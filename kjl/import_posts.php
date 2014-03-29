<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 14-2-15
 * Time: 15:05
 */
require(dirname(__FILE__) . '/../wp-load.php');
require_once(dirname(__FILE__). '/function_utils.php');

/*$fd_ts = cal_first_day_timestamp(time());
echo date("Y-m-d H:i", $fd_ts), "\n";
$ld_ts = cal_last_day_timestamp(time());
echo date("Y-m-d H:i", $ld_ts), "\n";
$str1 = "2009年10月21日 16:10";
$str2 = "2009年10月22日 16:10";
echo get_common_length($str1, $str2), "\n";
echo get_end_time_show_string("2014/01/22 10:30", "2014/01/22 12:30"), "\n";
echo get_end_time_show_string("2014/01/22 10:30", "2014/01/26 12:30"), "\n";
echo get_end_time_show_string("2014/01/22 10:30", "2015/01/26 12:30"), "\n";

echo parse_date_string("2009-10-21 16:10");
exit; */

function update_posts($input_dir_name, $mode="update") {
    $already_existing_urls = get_all_post_metadata_by_name('url');
    if (!file_exists($input_dir_name) || !is_dir($input_dir_name)) {
        exit;
    }
    $input_dir = dir($input_dir_name);
    while($entry = $input_dir->read()) {
        if (strpos($entry, ".") === 0) {
            continue;
        }
        $file_name = $input_dir_name.'/'.$entry;
        echo $file_name, "\n";
        $content = read_json_file($file_name);
        $post_id = -1;
        if (isset($already_existing_urls[$content['url']])) {
            if (strcmp($mode, "update") != 0) {
                continue;
            }
            $post_id = $already_existing_urls[$content['url']];
        }
        import_one_post($content, $post_id);
    }
}

/*delete_all_category_ids();
exit; */
/*$args = array('description' => '各类艺术', 'category_parent' => 0);
$new_cat_id = wp_insert_term('艺术', "category", $args);
print_r($new_cat_id);
exit; */

$input_dir_name = "/Users/king/Documents/WhatIHaveDone/KaiJiangLou/all_posts";
$update_mode = "update";
update_posts($input_dir_name, $update_mode);

