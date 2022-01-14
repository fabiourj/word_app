<?php
add_action( 'rest_api_init', 'add_custom_category_route');

function add_custom_category_route(){
	register_rest_route( 'wordroid/v4', '/categories', array(
        'methods' => 'GET',
        'callback' => 'get_custom_categories',
    ));
}

function get_custom_categories(){
	$cat = get_categories( array(
		'orderby' => 'name',
		'order'   => 'ASC'
	) );
	$arr = array();
	foreach($cat as $c){
		unset($c['filter']);
		array_push($arr,$c);
	}
	return $arr;
}

function prepare_restful_categories($response, $item, $request) {
    if ($request['app']=='wordroid') {
    	unset( $response->data['description'] );
    	unset( $response->data['link'] );
    	unset( $response->data['slug'] );
    	unset( $response->data['taxonomy'] );
    	unset( $response->data['meta'] );
    	unset( $response->data['_links'] );
		if($response->data['cmb2']['wordroid4_fields']['hide_category_w4']=='on'){
			$response->data['hidden'] = true;
			//unset($response);
		}else{
			$response->data['hidden'] = false;
		}
		unset( $response->data['cmb2'] );
    	return $response;
    }else{
    	return $response;
    }
}
add_filter('rest_prepare_category', 'prepare_restful_categories', 10, 3);


function prepare_restful_tags($response, $item, $request) {
    if ($request['app']=='wordroid') {
    	unset( $response->data['description'] );
    	unset( $response->data['link'] );
    	unset( $response->data['slug'] );
    	unset( $response->data['taxonomy'] );
    	unset( $response->data['meta'] );
    	return $response;
    }else{
    	return $response;
    }
}
add_filter('rest_prepare_post_tag', 'prepare_restful_tags', 10, 3);