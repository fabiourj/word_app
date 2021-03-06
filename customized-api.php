<?php
/*add_filter( 'rest_allow_anonymous_comments', '__return_true' );
add_action( 'rest_api_init','register_settings_route');*/

/**
 * Load translation files.
 *
 * @since  1.2.0
 */
/*function register_settings_route(){
	register_rest_route( 'wordroid/v2', '/settings', array(
    'methods' => 'GET',
    'callback' => 'get_settings_data',
  ) );
}

function get_settings_data(){
	$settings_saved = get_option('settings_saved');
	if($settings_saved=='true')
		$settings_saved = true;
	else
		$settings_saved = false;
	//Update Title and Body
	$update_title = substr(wordroid_get_option('wordroid-update','update_title'),0,50);
	if($update_title==false)
		$update_title = null;
	$update_body = wordroid_get_option('wordroid-update','update_body');
	if($update_body==false)
		$update_body = null;
	//User key
	$user_key = wordroid_get_option('wordroid-settings','app_user_key');
	if(!$user_key)
		$user_key = null;
	//App Version
	$version = wordroid_get_option('wordroid-update','version');
	$version = (int)$version;
	//Categories on home screen
	$category = wordroid_get_option('wordroid-config','home_screen_categories');
	if($category==false)
		$category = array();
	//Force Update
	$force_update = wordroid_get_option('wordroid-update','force_update');
	if($force_update == 'on')
		$force_update = true;
	//Slider Settings
	$slider_enabled = wordroid_get_option('wordroid-config','slider_enabled');
	if($slider_enabled == 'on')
		$slider_enabled = true;
	//Sections settings
	$sections = wordroid_get_option('wordroid-config','wordroid_section_group');
	$settings_data = array(
	    'app_title' => wordroid_get_option('wordroid-config','app_name'),
	    'user_key' => $user_key,
		'settings_saved' => true,
	    'update_title' => $update_title,
	    'update_body' => $update_body,
	    'app_version' => $version,
	    'force_update' => $force_update,
		'slider_enabled'   => $slider_enabled,
		'slider_cat'   => wordroid_get_option('wordroid-config','slider_category'),
		'sections' => format_sections($sections),
		'categories' => $category,
	);
	return $settings_data;
}

function format_sections($sections){
	if($sections == false)
		$sections = array();
	$newarray = array();
	foreach($sections as $section){
		$item = array(
			'title' => $section['title'],
			'category_id' => (int)$section['category_id'],
			'type' => (int) $section['type'],
			'post_count' => (int) $section['post_count'],
			'image' => ($section['image']!=null||$section['image']!=false) ? $section['image'] : null,
			'posts' => get_post_by_category((int)$section['category_id'],(int) $section['post_count']),
		);
		array_push($newarray,$item);
	}
	return $newarray;
}

function wordroid_get_option($prefix,$key = '', $default = false ) {
		if ( function_exists( 'cmb2_get_option' ) ) {
			// Use cmb2_get_option as it passes through some key filters.
			return cmb2_get_option( $prefix, $key, $default );
		}
		// Fallback to get_option if CMB2 is not loaded yet.
		$opts = get_option( $prefix, $default );
		$val = $default;
		if ( 'all' == $key ) {
			$val = $opts;
		} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
			$val = $opts[ $key ];
		}
		return $val;
}
function better_rest_api_featured_images_load_translations() {
    load_plugin_textdomain( 'better-rest-api-featured-images', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
function wordroid_rest_api_comments($object, $field_name, $request){
	if(!empty($object['id'])){
		$comment_id = $object['id'];
	}else{
		return null;
	}
	$args = array(
		'parent' => $comment_id, // use user_id
		'count' => true //return only the count
	);
	$comments = get_comments($args);
	return $comments;
}

// Add filter to respond with next and previous post in post response.
add_filter( 'rest_prepare_post', function( $response, $post, $request ) {
  // Only do this for single post requests.
        global $post;
        $response->data['comment_count'] = (int)get_comments_number( $post->ID);
    return $response;
}, 10, 3 );



function wordroid_prev_post($object, $field_name, $request){

}

function wordroid_rest_api_author($object, $field_name, $request){
	if(!empty($object['author'])){
		$author_id = $object['author'];
	}else{
		return null;
	}
	$author_name = get_the_author_meta( 'display_name' , $author_id );
	return apply_filters( 'wordroid_author', $author_name, $author_id );
}




function wordroid_rest_api_categories($object, $field_name, $request){
	if(!empty($object['categories'])){
		$categories = $object['categories'];
	}else{
		return null;
	}
	if(sizeof($categories)==0){
		return null;
	}
	$category_obj = [];
	
	foreach($categories as $cat) {
		$category = get_category($cat);
		$array = [];
		$array['id'] = $cat;
		$array['name'] = $category->name;
		$array['description'] = $category->description;
		$array['slug'] = $category->slug;
		$array['count'] = $category->count;
		$array['parent'] = $category->parent;
		array_push($category_obj,$array);
	}
	return apply_filters( 'wordroid_categories', $category_obj, $image_id );
}
function better_rest_api_featured_images_get_field( $object, $field_name, $request ) {

	// Only proceed if the post has a featured image.
	if ( ! empty( $object['featured_media'] ) ) {
		$image_id = (int)$object['featured_media'];
	} elseif ( ! empty( $object['featured_image'] ) ) {
		// This was added for backwards compatibility with < WP REST API v2 Beta 11.
		$image_id = (int)$object['featured_image'];
	} else {
		return null;
	}

	$image = get_post( $image_id );

	if ( ! $image ) {
		return null;
	}
	// This is taken from WP_REST_Attachments_Controller::prepare_item_for_response().
	$featured_image['id']            = $image_id;
	$featured_image['alt_text']      = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
	$featured_image['caption']       = $image->post_excerpt;
	$featured_image['description']   = $image->post_content;
	$featured_image['source_url']    = wp_get_attachment_url( $image_id );
	$featured_image['medium_large']  = get_the_post_thumbnail_url($image->post_parent,'medium_large'); 
	$featured_image['post_thumbnail']= get_the_post_thumbnail_url($image->post_parent,'post-thumbnail'); 
	return apply_filters( 'better_rest_api_featured_image', $featured_image, $image_id );
}

/*function get_post_by_category($category){
    $posts = array();
    $args = array(
        'posts_per_page'   => 10,
        'cat'              => $category,
        'orderby'          => 'post_date',
        'post_type'        => 'post',
        'post_status'      => array('publish', 'draft', 'pending' ),
        'author'           => 1,
    ); 
    
    $the_query = new WP_Query($args);
    while ( $the_query->have_posts() ) : $the_query->the_post();
        $post = array();
        $post['title'] = get_the_title();
        $post['img'] = get_the_post_thumbnail_url();
		$post['id'] = get_the_ID();
        array_push($posts,$post);
    endwhile;
    wp_reset_postdata();

    return $posts;
}*/
