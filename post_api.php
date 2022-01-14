<?php
add_action('rest_api_init', 'register_fields' );
add_action( 'wp_head', 'wordroid_track_post_views_wordroid4');

function register_fields(){

    //Register featured image for posts
    register_rest_field( 'post',
        'featured_img',
        array(
            'get_callback'    => 'get_rest_featured_image_v4',
            'update_callback' => null,
            'schema'          => null,
        )
    );
    
    
    //Register category details
    register_rest_field( 'post',
		'categories_details',
		array(
			'get_callback' => 'get_post_category_details',
			'update_callback' => null,
			'schema'       => null,
		)
	);

	//Register tags details
    register_rest_field( 'post',
		'tags_details',
		array(
			'get_callback' => 'get_post_tags_details',
			'update_callback' => null,
			'schema'       => null,
		)
	);
    
    //Register comment count
    register_rest_field( 'post',
		'comment_count',
		array(
			'get_callback' => 'get_post_comment_count',
			'update_callback' => null,
			'schema'       => null,
		)
	);


    //Register comment count
    register_rest_field( 'post',
		'author_name',
		array(
			'get_callback' => 'get_wordroid4_author_name',
			'update_callback' => null,
			'schema'       => null,
		)
	);

	//Register children field
    register_rest_field( 'comment',
		'child_count',
		array(
			'get_callback' => 'get_comment_child_field',
			'update_callback' => null,
			'schema'       => null,
		)
	);

	//Post Views Count
    register_rest_field( 'post',
		'post_views',
		array(
			'get_callback' => 'get_wordroid4_post_views',
			'update_callback' => null,
			'schema'       => null,
		)
	);
}

function post_fetured_image_json( $data, $post, $context ) {
	if ($context['app']=='wordroid') {
		unset( $data->data['featured_media'] ); // remove the featured_media field
		unset( $data->data['date'] );
		unset( $data->data['link'] );
		unset( $data->data['sticky'] );
		unset( $data->data['date_gmt'] );
		unset( $data->data['modified_gmt'] );
		unset( $data->data['categories_detail'] );
		unset( $data->data['better_featured_image'] );
		unset( $data->data['guid'] );
		unset( $data->data['ping_status'] );
		unset( $data->data['slug'] );
		unset( $data->data['status'] );
		unset( $data->data['type'] );
		unset( $data->data['content'] );
		unset( $data->data['categories'] );
		unset( $data->data['template'] );
		unset( $data->data['tags'] );
		unset( $data->data['categories_details'] );
		unset( $data->data['tags_details'] );
		unset( $data->data['excerpt'] );
		unset( $data->data['meta'] );
		unset( $data->data['format'] );
		unset( $data->data['_links'] );
		$data->data['title'] = $data->data['title']['rendered'];
		$data->data['modified'] = get_the_date( get_option('date_format') );

		if ($data->data['comment_status']=='open') {
			$data->data['comment_status'] = true;
		}
		return $data;
	}else{
		return $data;
	}
	
}
add_filter( 'rest_prepare_post', 'post_fetured_image_json', 10, 3 );

function get_rest_featured_image_v4( $response, $field_name, $request ) {
    return get_the_post_thumbnail_url($request['id'] ,'post-thumbnail');
}

function get_post_category_details($object, $field_name, $request){
	if ($request['context']!='embed') {
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
			$array['id'] = $category->term_id;
			$array['name'] = $category->name;
			$array['count'] = $category->count;
			$array['parent'] = $category->parent;
			array_push($category_obj,$array);
		}
		return apply_filters( 'wordroid4_categories', $category_obj );
	}
	
}

function get_post_tags_details($object, $field_name, $request){
	if ($request['context']!='embed') {
		if(!empty($object['tags'])){
			$categories = $object['tags'];
		}else{
			return array();
		}
		if(sizeof($categories)==0){
			return array();
		}
		$category_obj = [];
		foreach($categories as $cat) {
			$category = get_tag($cat);
			$array = [];
			$array['id'] = $category->term_id;
			$array['name'] = $category->name;
			$array['count'] = $category->count;
			$array['parent'] = $category->parent;
			array_push($category_obj,$array);
		}
		return apply_filters( 'wordroid4_categories', $category_obj );
	}
}

function get_comment_child_field($object, $field_name, $request){
		$id = $object['id'];
		$childcomments = get_comments(array(
		    'status'    => 'approve',
		    'order'     => 'DESC',
		    'hierarchical'	=> true,
		    'parent'    => $id
		));
		return count($childcomments);
}

function get_wordroid4_author_name($object, $field_name, $request){
	$author_id = $object['author'];
	if ($request['context']!='embed'&&$request['app']!='wordroid') {
		wordroid_set_post_views_wordroid4($object['id']);
	}
    $author_name = get_the_author_meta( 'display_name' , $author_id );
    return $author_name;
}


function get_post_comment_count( $object, $field_name, $request ){
    $id = $object['id'];
    return (int)get_comments_number( $id);
    
}

function get_hompage_blocks($object, $field_name, $request){

}

function get_wordroid4_post_views($object, $field_name, $request ){
	$id = $object['id'];
	return (int)get_post_meta($id, 'wordroid_post_views_count',true);
}

function wordroid_set_post_views_wordroid4($postID) {
    $count_key = 'wordroid_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

function wordroid_track_post_views_wordroid4 ($post_id) {
    if ( !is_single() ) return;
    if ( empty ( $post_id) ) {
        global $post;
        $post_id = $post->ID;    
    }
    wordroid_set_post_views_wordroid4($post_id);
}
