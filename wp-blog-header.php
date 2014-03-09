<?php
/**
 * Loads the WordPress environment and template.
 *
 * @package WordPress
 */

if ( !isset($wp_did_header) ) {

	$wp_did_header = true;

	require_once( dirname(__FILE__) . '/wp-load.php' );

	//wp();
    //echo 'b'.'<br>';
    $args = '';
    if (isset($_GET['stage'])) {
        //echo $_GET['stage'].'<br>';
        $stage = $_GET['stage'];
        $cur_time = time();
        $first_day_the_week = cal_first_day_timestamp($cur_time);
        $last_day_the_week = cal_last_day_timestamp($cur_time);
        if ($stage == -1) {
            $args = generate_history_args($first_day_the_week, $last_day_the_week);
        } else if ($stage == 0) {
            $args = generate_now_args($first_day_the_week, $last_day_the_week);
        } else if ($stage == 1) {
            $args = generate_future_args($first_day_the_week, $last_day_the_week);
        }
    }
    kjl_wp('', $args);


	require_once( ABSPATH . WPINC . '/template-loader.php' );
}

function kjl_wp( $query_vars = '', $extra_query_vars='' ) {
    global $wp, $wp_query, $wp_the_query;
    kjl_main( $query_vars, $extra_query_vars );

    if ( !isset($wp_the_query) )
        $wp_the_query = $wp_query;
}

function kjl_main($query_vars, $extra_query_vars='') {
    global $wp;
    $wp->init();
    $wp->parse_request($query_vars);
    if (is_array($extra_query_vars)) {
       foreach($extra_query_vars as $key => $value) {
           $wp->set_query_var($key, $value);
       }
    }
    $wp->send_headers();
    $wp->query_posts();
    $wp->handle_404();
    $wp->register_globals();

    /**
     * Fires once the WordPress environment has been set up.
     *
     * @since 2.1.0
     *
     * @param WP &$this Current WordPress environment instance (passed by reference).
     */
    do_action_ref_array( 'wp', array( &$wp ) );
}

function generate_history_args($first_day_the_week, $last_day_the_week) {
    $args = array(
        'meta_query' => array(
            array(
                'key' => 'start_time',
                'value' => $first_day_the_week,
                'compare' => '<'
            )
        )
    );
    return $args;
}

function generate_now_args($first_day_the_week, $last_day_the_week) {
    $args = array(
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'start_time',
                'value' => $first_day_the_week,
                'compare' => '>='
            ),
            array(
                'key' => 'start_time',
                'value' => $last_day_the_week,
                'compare' => '<'
            )
        )
    );
    return $args;
}

function generate_future_args($first_day_the_week, $last_day_the_week) {
    $args = array(
        'meta_query' => array(
            array(
                'key' => 'start_time',
                'value' => $last_day_the_week,
                'compare' => '>='
            )
        )
    );
    return $args;
}
