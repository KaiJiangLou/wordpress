<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 14-2-15
 * Time: 15:05
 */
require(dirname(__FILE__) . '/../wp-load.php');
require_once(dirname(__FILE__). '/function_utils.php');

$fd_ts = cal_first_day_timestamp(time());
echo date("Y-m-d H:i", $fd_ts), "\n";
$ld_ts = cal_last_day_timestamp(time());
echo date("Y-m-d H:i", $ld_ts), "\n";
$str1 = "2009年10月21日 16:10";
$str2 = "2009年10月22日 16:10";
echo get_common_length($str1, $str2), "\n";

echo parse_date_string("2009-10-21 16:10");
exit;

$input_dir_name = "/Users/king/Documents/WhatIHaveDone/KaiJiangLou/csdn/output";
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
    import_one_post($content);
}
exit;


$post_id = 20;


