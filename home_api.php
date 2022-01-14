<?php
add_action( 'rest_api_init', 'add_homepage_route');
add_filter( 'rest_allow_anonymous_comments', '__return_true' );

function add_homepage_route(){
	register_rest_route( 'wordroid/v4', '/homepage', array(
        'methods' => 'GET',
        'callback' => 'get_home_page_sections',
    ));
    register_rest_route( 'wordroid/v4', '/info', array(
        'methods' => 'GET',
        'callback' => 'get_home_page_settings',
    ));
	
	register_rest_route( 'wordroid/v4', '/settings', array(
        'methods' => 'GET',
        'callback' => 'get_home_page_settings_beta',
    ));

     register_rest_route( 'wordroid/v4', '/comments', array(
        'methods' => 'GET',
        'callback' => 'wordroid_get_comments',
    ));
}

function wordroid_get_comments(){
 
         $args = array(
            'parent' => 2,
            'hierarchical' => true,
         );
        $questions = get_comments($args);
        return $questions;
}

function get_home_page_sections(){
	$randomizeSection = wordroid4_get_option('wordroid4-config','randomize_section_checkbox');


	$sections = wordroid4_get_option('wordroid4-config','wordroid_section_group');
	$result = array();

	foreach($sections as $section){

		$randomItems = isset($section['randomize_content_checkbox']) ? $section['randomize_content_checkbox'] : 'off';
		if($randomItems=='on')
			$randomItems = true;
		else
			$randomItems = false;
		$contentType = (int)$section['content_type'];
		if($section['category_multiple_choose']==null){
			$category = null;
		}else{
			$category = implode(', ',$section['category_multiple_choose']);
		}

		if(isset($section['tag_multiple_select'])){
			if($section['tag_multiple_select']==null){
				$tags = null;
			}else{
				$tags = implode(', ',$section['tag_multiple_select']);
			}
		}
		
		
		switch ($contentType) {
			case 1:
					$item = array(
						"title" => $section['title'],
						"layout_type" => (int)$section['layout_type'],
						"content_type" => (int)$section['content_type'],
						"category" => $category,
						"tags" => $tags,
						"items" => get_post_by_category_wordroid4($section['category_multiple_choose'],(int) $section['post_count'],$randomItems)
					);
					array_push($result,$item);
				break;
			case 2:
					$item = array(
						"title" => $section['title'],
						"layout_type" => (int)$section['layout_type'],
						"content_type" => (int)$section['content_type'],
						"category" => $category,
						"tag" => $tags,
						"items" => get_post_by_category_wordroid4((int)$section['category_select'],(int) $section['post_count'],$randomItems)
					);
					array_push($result,$item);	
				break;
			case 3:
					$item = array(
						"title" => $section['title'],
						"layout_type" => 5,
						"content_type" => (int)$section['content_type'],
						"category" => implode( ",", $section['category_multiple_choose'] ),
						"tag" => null,
						"items" => get_terms_by_ids_wordroid4($section['category_multiple_choose'],(int) $section['post_count'],$randomItems,'category')
					);
					array_push($result,$item);
				break;
			case 4:
					$item = array(
						"title" => $section['title'],
						"layout_type" => 5,
						"content_type" => (int)$section['content_type'],
						"category" => null,
						"tag" => null,
						"items" => get_terms_by_ids_wordroid4($section['tag_multiple_select'],(int) $section['post_count'],$randomItems,'post_tag')
					);
					array_push($result,$item);
				break;	
			default:
				//array_push($result,"Default Type");
				break;
		}
	}
	
	if ($randomizeSection) {
		shuffle($result);
	}
	return $result;
}

function get_b_items_array(){
	$result = new stdClass();
	$visibility = wordroid4_get_option('wordroid4-bottom-nav','bnav_visibility');
	$bc = wordroid4_get_option('wordroid4-bottom-nav','back_bnav_color');
	$ac = wordroid4_get_option('wordroid4-bottom-nav','selected_bnav_color');
	$uc = wordroid4_get_option('wordroid4-bottom-nav','unselected_bnav_color');
	$sl = wordroid4_get_option('wordroid4-bottom-nav','bnav_show_labels');
	$result->visibility = isset($visibility) ? (bool)$visibility : true;
	$result->background_color = isset($bc) ? $bc : "#ffffff";
	$result->checked_item_color = isset($ac) ? $ac : "#1976D2";
	$result->unchecked_item_color = isset($uc) ? $uc : "#666666";
	$result->show_labels = isset($sl) ? $sl : 'always';
	$a=wordroid4_get_option('wordroid4-bottom-nav','bottom_nav_group');
	$i=array();
	if(isset($a)&&is_array($a)){
		$a=array_slice($a, 0, 5, true);
		foreach($a as $b){
			if(!isset($b['item_icon'])){
				continue;
			}
			$x=array(
				'icon' => isset($b['item_icon']) ? $b['item_icon'] : null,
				'icon_color' => isset($b['item_color']) ? $b['item_color'] : '#000000',
				'title' => isset($b['item_title']) ? $b['item_title'] : '',
				'destination' => (int)$b['item_destination'],
				'data' => isset($b['item_data']) ? $b['item_data'] : null ,
			);
			array_push($i,$x);
		}
	}
	$result->items=$i;
	return $result;
}

function get_drawer_items(){
	$result = new stdClass();
	$v = wordroid4_get_option('wordroid4-configure-app','nav_visibility');
	$hv = wordroid4_get_option('wordroid4-configure-app','nav_header');
	$bc = wordroid4_get_option('wordroid4-configure-app','nav_header_color');
	$result->visibility = isset($v) ? (bool)$v : true;
	$result->header_visibility = isset($hv) ? (bool)$hv : true;
	$result->header_background_color = isset($bc) ? $bc : "#1976D2";
	$a=wordroid4_get_option('wordroid4-configure-app','wiki_test_repeat_group');
	$i=array();
	if(isset($a)&&is_array($a)){
		foreach($a as $b){
			if(!isset($b['item_title'])){
				continue;
			}
			$x=array(
				'icon' => isset($b['item_icon']) ? $b['item_icon'] : '',
				'icon_color' => isset($b['item_color']) ? $b['item_color'] : '#000000',
				'title' => isset($b['item_title']) ? $b['item_title'] : '',
				'destination' => isset($b['item_destination']) ? (int)$b['item_destination'] : 0,
				'data' => isset($b['item_data']) ? check_print($b['item_data']) : null ,
			);
			array_push($i,$x);
		}
	}
	$result->items=$i;
	return $result;
}

function check_print($var){
	if(isset($var)&&$var!=""){
		return $var;
	}else{
		return null;
	}
}

function p_s(){
	$result = new stdClass();
	$ba = wordroid4_get_option('wordroid4-configure-app-defaults','banner_ads');
	$ia = wordroid4_get_option('wordroid4-configure-app-defaults','itn_ads');
	$iaf = wordroid4_get_option('wordroid4-configure-app-defaults','iaf');
	$psf = wordroid4_get_option('wordroid4-configure-app-defaults','post_speak_feature');
	$copy = wordroid4_get_option('wordroid4-configure-app-defaults','copy_feature');
	$oel = wordroid4_get_option('wordroid4-configure-app-defaults','open_external_links');
	$result->banner_ads_enabled = isset($ba) ? (bool)$ba : true;
	$result->interstitial_ads_enabled = isset($ia) ? (bool)$ia : false;
	$result->interstitial_ad_frequency = isset($iaf) ? (int)$iaf : 7;
	$result->speak_enabled = isset($psf) ? (bool)$psf : true;
	$result->content_copy_enabled = isset($copy) ? (bool)$copy : true;
	$result->external_links = isset($oel) ? $oel : 'app';
	return $result;
}

function plp(){
	$result = new stdClass();
	$ba = wordroid4_get_option('wordroid4-configure-app-defaults','list_banner_ads');
	$na = wordroid4_get_option('wordroid4-configure-app-defaults','list_native_ads');
	$naf = wordroid4_get_option('wordroid4-configure-app-defaults','list_native_ads_freq');
	$result->banner_ads_enabled = isset($ba) ? (bool)$ba : true;
	$result->native_ads_enabled = isset($na) ? (bool)$na : false;
	$result->native_ads_frequency = isset($iaf) ? (int)$iaf : 7;
	$result->item_layout= "enabled";
	return $result;
}


function get_home_page_settings_beta(){
	$result = array();

	//App Update
	$code = wordroid4_get_option('wordroid4-plugin-activate','ipc_code');
	if(!isset($code)||$code==""||$code=="Invalid Purchase Code. Please double check your purchase code."){
		$code = wordroid4_get_option('wordroid4-plugin-activate','manual_activation_code');
	}
	$set = encrypt_decrypt('decrypt',$code);
	$obj = json_decode($set,JSON_UNESCAPED_SLASHES);
	unset($obj['b']);
	$force_update =  wordroid4_get_option('wordroid4-update','force_update') == "on" ? true : false;
	
	$app_name = wordroid4_get_option('wordroid4-configure-app-defaults','app_title');
	$app_intro = wordroid4_get_option('wordroid4-configure-app-defaults','app_intro');
	$signIn = wordroid4_get_option('wordroid4-configure-app-defaults','app_signin');
	$about = wordroid4_get_option('wordroid4-configure-app-defaults','about_page');
	$privacy = wordroid4_get_option('wordroid4-configure-app-defaults','privacy_page');
	//$nav_drawer->items = $drawer;
	$settings = new stdClass();
	$settings->app_name = isset($app_name) ? $app_name : get_bloginfo('name');
	$settings->app_intro = isset($app_intro) ? (bool)$app_intro : false;
	$settings->signin = isset($app_intro) ? (bool)$app_intro : false;
	$settings->about_url = isset($about) ? $about : get_bloginfo('url');
	$settings->privacy_url = isset($privacy) ? $privacy : get_bloginfo('url');
	$settings->post_settings = p_s();
	$settings->post_list_settings = plp();
	$settings->bottom_navigation = get_b_items_array();
	$settings->nav_drawer = get_drawer_items();
	
	
	$updateArray = array(
		'title' => wordroid4_get_option('wordroid4-update','update_title'),
		'msg'  => wordroid4_get_option('wordroid4-update','update_body'),
		'version' => (double)wordroid4_get_option('wordroid4-update','version'),
		'force_update' => $force_update
	);
	$cats = wordroid4_get_option('wordroid4-configure-app-defaults','hide_category_multiple');
		if($cats==null){
			$category = null;
		}else{
			$category = implode(',',$cats);
		}
	$result = array(
		'update' => $updateArray,
		'hidden_cats' => $category,
		'info' => $obj,
		'settings' => $settings,
	);
	

	return $result;
}

function get_home_page_settings(){
	$result = array();

	//App Update
	$force_update =  wordroid4_get_option('wordroid4-update','force_update') == "on" ? true : false;
	$code = wordroid4_get_option('wordroid4-plugin-activate','ipc_code');
	if(!isset($code)||$code==""||$code=="Invalid Purchase Code. Please double check your purchase code."){
		$code = wordroid4_get_option('wordroid4-plugin-activate','manual_activation_code');
	}
	$set = encrypt_decrypt('decrypt',$code);
	$obj = json_decode($set,JSON_UNESCAPED_SLASHES);
	unset($obj['b']);
	$settings = encrypt_decrypt('decrypt',wordroid4_get_option('wordroid4-plugin-activate','ipc_code'));
	$updateArray = array(
		'title' => wordroid4_get_option('wordroid4-update','update_title'),
		'msg'  => wordroid4_get_option('wordroid4-update','update_body'),
		'version' => (double)wordroid4_get_option('wordroid4-update','version'),
		'force_update' => $force_update
	);
	$cats = wordroid4_get_option('wordroid4-configure-app','hide_category_multiple');
		if($cats==null){
			$category = null;
		}else{
			$category = implode(',',$cats);
		}
	$result = array(
		'update' => $updateArray,
		'hidden_cats' => $category,
		'settings' => $obj,
	);
	

	return $result;
}

function get_terms_by_ids_wordroid4($categories,$count,$randomize,$taxonomy){
	$result = array();
	if(!empty($categories)){
		foreach($categories as $cat){
			$category = get_category((int)$cat);
			$data = array();
			$data['id'] = $category->term_id;//$category['term_id'];
			$data['title'] = $category->name;//$category['name'];
			$data['count'] = $category->count;//$category['count'];
			array_push($result,$data);
		}
	}else{
		if($randomize){
			$randomize = 'rand';
		}else{
			$randomize = 'name';
		}
		$categories = get_terms( array(
			'orderby' => $randomize,
			'taxonomy' => $taxonomy
		));

		if(count($categories)<$count)
			$count = count($categories);
		
		for($i=0;$i<$count;$i++){
			$data = array();
			$data['id'] = $categories[$i]->term_id;//$category['term_id'];
			$data['title'] = $categories[$i]->name;//$category['name'];
			$data['count'] = $categories[$i]->count;//$category['count'];
			array_push($result,$data);
		}
	}
	return $result;
}

function get_post_by_category_wordroid4($category,$count,$orderby){
    $posts = array();
    if($count == 0){
    	$count = 10;
    }
    if($orderby){
    	$orderby = 'rand';
    }else{
    	$orderby = 'post_date';
    }
    $args = array(
        'posts_per_page'   => $count,
        'cat'              => $category,
        'orderby'          => $orderby,
        'post_type'        => 'post',
        'post_status'      => array('publish'),
    ); 
    
    $the_query = new WP_Query($args);
    while ( $the_query->have_posts() ) : $the_query->the_post();
        $post = array();
        $post['title'] = get_the_title();
        $post['featured_img'] = get_the_post_thumbnail_url();
		$post['id'] = get_the_ID();
		$post['modified'] = get_the_date();
		$post['post_views'] = (int)get_post_meta(get_the_ID(),'wordroid_post_views_count',true);
		$post['author_name'] = get_the_author_meta( 'display_name');
		$post['comment_count'] = (int)get_comments_number(get_the_ID());
        array_push($posts,$post);
    endwhile;
    wp_reset_postdata();

    return $posts;
}

function wordroid4_get_option($prefix,$key = '', $default = null ) {
		if ( function_exists( 'cmb2_get_option' ) ) {
			// Use cmb2_get_option as it passes through some key filters.
			return cmb2_get_option( $prefix, $key, $default );
		}
		$opts = get_option( $prefix, $default );
		$val = $default;
		if ( 'all' == $key ) {
			$val = $opts;
		} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
			$val = $opts[ $key ];
		}
		return $val;
}



function encrypt_decrypt($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = APP_NAME;
        $secret_iv = 'HelloWorldIsAnExampleKey';
        $key = hash('sha256', $secret_key);
        
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
}