<?php

add_action( 'rest_api_init','register_random_route_wordroid4');
add_action( 'rest_api_init','register_popular_posts_wordroid4');
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);


function register_random_route_wordroid4(){
    register_rest_route( 'wordroid/v2', '/random', array(
        'methods' => 'GET',
        'callback' => 'get_random_posts_wordroid4',
      ) );
}

function register_popular_posts_wordroid4(){
	register_rest_route( 'wordroid/v2', '/popular', array(
        'methods' => 'GET',
        'callback' => 'get_popular_posts_wordroid4',
      ) );
}

function get_popular_posts_wordroid4($count){
	$posts = array();
	$popularpost = new WP_Query( 
		array( 
			'posts_per_page' => $count, 
			'meta_key' => 'wordroid_post_views_count', 
			'orderby' => 'meta_value_num', 
			'order' => 'DESC'  
		) 
	);
	if ( $popularpost->have_posts() ) {
    	while ( $popularpost->have_posts() ) {
			$post = array();
        	$popularpost->the_post();
			$post['title'] = get_the_title();
            $post['id'] = get_the_ID();
            $post['img'] = get_the_post_thumbnail_url();
			array_push($posts,$post);
    	}
    	wp_reset_postdata();
	} else { 
		$posts = null;
	}
	return $posts;
}

function get_random_posts_wordroid4(){
	$args = array(
        'post_type' => 'post',
        'orderby'   => 'rand',
        'posts_per_page' => 5, 
    );
 	$posts = array();
	$the_query = new WP_Query( $args );
 
	if ( $the_query->have_posts() ) {
    	while ( $the_query->have_posts() ) {
			$post = array();
        	$the_query->the_post();
			$post['title'] = get_the_title();
            $post['id'] = get_the_ID();
            $post['img'] = get_the_post_thumbnail_url();
			array_push($posts,$post);
    	}
    	wp_reset_postdata();
	} else { 
		$posts = null;
	}
    return $posts;
}

function author_admin_notice(){
	

	$code = wordroid4_get_option('wordroid4-plugin-activate','ipc_code');
	if(!isset($code)||$code==""||$code==" "){
		$code = wordroid4_get_option('wordroid4-plugin-activate','manual_activation_code');
	}

	global $pagenow;
	$set = encrypt_decrypt('decrypt',$code);
	$obj = json_decode($set,JSON_UNESCAPED_SLASHES);
  
}
add_action('admin_notices', 'author_admin_notice');