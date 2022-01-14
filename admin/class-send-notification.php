<?php
	

	function wp_get_option4($prefix,$key = '', $default = false ) {
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

	function send_notification_wordroid4(){
		//echo "<h2>Sending notification</h2>";
	}

	function post_notification_wordroid4($title,$body,$value,$img,$type){
		$appid = wp_get_option4('wordroid4-settings','os_app_id_w4');
		$segment = wp_get_option4('wordroid4-settings','onesignal_test_notification');
		if($segment == 'on'){
			$segment = 'Test';
		}else{
			$segment = 'All';
		}
	    $content = array(
	        "en" => $body == null ? "" : $body
	        );
	    $headings = array(
	        "en" => html_entity_decode($title)
	    );
		$fields = array(
	        'app_id' => $appid,
	        'included_segments' => array($segment),
	        'data' => array(
	        	"type" => $type,
	        	"value" => $value,
				"title" => $title,
				"message" => $body
	        ),
	        'big_picture' => $img,
	        'headings' => $headings,
	        'contents' => $content
	    );
		$response = wordroidsendMessage_wordroid4($fields);
		
		
	}

	function post_transition_action_wordroid4($new_status, $old_status, $post){
		 if ($old_status == 'publish' && $new_status == 'publish' && 'post' == get_post_type($post)) {
			 //Post Updated Notification
		 	$notify = wp_get_option4('wordroid4-settings','enable_updatepost_notify_w4');
		 	if($notify=='on'){
			 	$type = "post";
				$title =  wp_get_option4('wordroid4-settings','update_notify_title_w4');
				$post_title = html_entity_decode(get_the_title($post));
				$post_id 	= get_the_ID($post);
				$thumbnail = get_the_post_thumbnail_url($post,'full');
				$response =  post_notification_wordroid4($title,$post_title,$post_id,$thumbnail,'post');
				if($response === NULL){
					return;
				}
			}
		}else if ($old_status != 'publish' && $new_status == 'publish' && 'post' == get_post_type($post)) {
			 //New Post Published Notification
			$notify = wp_get_option4('wordroid4-settings','enable_newpost_notify_w4');
			if($notify=='on'){
				$type = "post";
				$title =  wp_get_option4('wordroid4-settings','new_notify_title_w4');
				$post_title = html_entity_decode(get_the_title($post));
				$post_id 	= $post->ID;
				$thumbnail = get_the_post_thumbnail_url($post,'full');
				$response =  post_notification_wordroid4($title,$post_title,$post_id,$thumbnail,'post');
				if($response === NULL) return;
			}
		}
	}

	function wordroidsendMessage_wordroid4($fields_array){
		$appid = wp_get_option4('wordroid4-settings','os_app_id_w4');
	    $apikey = wp_get_option4('wordroid4-settings','os_api_key_w4');
		
	    $fields = json_encode($fields_array);
		
		$header = array('Content-Type: application/json; charset=utf-8',
	                                               'Authorization: Basic '.$apikey);
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ch, CURLOPT_HEADER, FALSE);
	    curl_setopt($ch, CURLOPT_POST, TRUE);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    

	    $response = curl_exec($ch);
	    curl_close($ch);

		$result_data = json_decode($response, true);
		
		return $result_data;
	}


	
	function sanitize( $value, $field_args, $field ){
		$curl = curl_init();
		if(!isset($value)||$value==""){
			$value = "0";
		}
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "http://app.itsanubhav.com/dash/api/register",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "ipc=".$value."&site_url=".get_site_url(),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		/*if ($err) {
		   return $err;
		} else {
		  
		}*/
		return $response;
	}

	function wiki_os_id($value, $field_args, $field){
		$code = wordroid4_get_option('wordroid4-plugin-activate','ipc_code');
		if(!isset($code)||$code==""||$code==MORE_INFO_REQ){
			$code = wordroid4_get_option('wordroid4-plugin-activate','manual_activation_code');
		}
		$set = encrypt_decrypt('decrypt',$code);
		$obj = json_decode($set,JSON_UNESCAPED_SLASHES);
		if($obj['a']==get_site_url()){
			return $value;
		}
		return ACT_NOW;
	}
	

?>
