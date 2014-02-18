<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 14-2-15
 * Time: 15:05
 */
require(dirname(__FILE__) . '/../wp-load.php');

$post_id = 20;

$my_post = array(
    'ID' => $post_id,
    'post_name' => 'name?',
    'post_title' => '自动发文章测试',
    'post_content' => 'Oh Yeah， 成功了！',
    'post_status' => 'publish',
    'post_category' => array(3),
    'tags_input' => '测试, 自动',
    #'tax_input' => array('address'=>'我的地址', 'start_time'=>'2014/02/29')
);

$status = wp_insert_post($my_post);
echo $status, "\n";

$post_metas = array('address'=>'我的地址', 'start_time'=>'2014/02/29');
foreach($post_metas as $key => $value) {
    update_post_meta($post_id, $key, $value);
}

$fields = get_post_custom($post_id);
echo get_post_meta($post_id, 'address', true), "\n";
print_r($fields);
