<?php
/**
 * Created by PhpStorm.
 * User: king
 * Date: 14-3-4
 * Time: 22:58
 */
require(dirname(__FILE__) . '/../wp-load.php');

wp();

if(!have_posts()) {
    exit;
}

$args = array('posts_per_page' => -1);
$posts_list = get_posts($args);
foreach ($posts_list as $post) {
    echo "post_id = ".get_the_ID()."\n";
    wp_delete_post(get_the_ID());
}
