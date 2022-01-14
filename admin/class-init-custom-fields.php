<?php

class Init_Custom_Fields_Wordroid4{

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	function wp_get_option($prefix,$key = '', $default = false ) {
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


	public function categories_custom_fields(){
		// Start with an underscore to hide fields from custom fields list
		$prefix = '_wordroid4_fields';

		/**
		 * Initiate the metabox
		 */
		$cmb = new_cmb2_box( array(
			'id'            => 'wordroid4_fields',
			'title'         => __( 'Test Metabox', 'cmb2' ),
			'object_types'  => array( 'term' ), // Post type
			'taxonomies'    => array( 'category'),
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => true, // Show field names on the left
			'show_in_rest' => WP_REST_Server::READABLE,
			 'cmb_styles' => true, // false to disable the CMB stylesheet
			// 'closed'     => true, // Keep the metabox closed by default
		) );


		$cmb->add_field( array(
			'name' => 'Hide category in WorDroid 4',
			'desc' => 'Check this box to hide the category in the WorDroid APP',
			'id'   => 'hide_category_w4',
			'type' => 'checkbox',
		) );
	}

	public function admin_settings_page(){
		$prefix = '_wordroid4_settings';

		$cmb = new_cmb2_box( array(
			'id'           => $prefix . 'wp-wordroid',
			'title'        => __( 'Settings', 'settings' ),
			'object_types'  => array( 'options-page' ),
			'option_key'      => 'wordroid4-settings', // The option key and admin menu page slug.
			'parent_slug'     => 'wordroid4-home', // Make options page a submenu item of
			'context'      => 'normal',
			'priority'     => 'default',
		) );


/*
		$cmb->add_field( array(
			'name' => 'Notification Settings',
			'desc' => 'Add your OneSignal App ID and OneSignal REST API Key. These will be used to send the notification to the users.',
			'type' => 'title',
			'id'   => 'wiki_test_title'
		) );
		$cmb->add_field( array(
			'name' => __( 'OneSingnal APP ID', 'settings' ),
			'id' => 'os_app_id_w4',
			'sanitization_cb' => 'wiki_os_id',
			'type' => 'text',
		) );
		$cmb->add_field( array(
			'name' => __( 'OneSingnal REST API Key', 'settings' ),
			'id' => 'os_api_key_w4',
			'type' => 'text',
		) );
		$cmb->add_field( array(
			'name' => 'New Post Notification',
			'desc' => 'Send Notification automatically when a new post is published',
			'id'   => 'enable_newpost_notify_w4',
			'type' => 'checkbox',
		) );
		$cmb->add_field( array(
			'name' => __( 'New Post Notification\'s Title', 'settings' ),
			'default' => 'New Post',
			'id' => 'new_notify_title_w4',
			'type' => 'text',
		) );
		$cmb->add_field( array(
			'name' => 'Updated Post Notification',
			'desc' => 'Send Notification automatically when a post is updated',
			'id'   => 'enable_updatepost_notify_w4',
			'type' => 'checkbox',
		) );
		$cmb->add_field( array(
			'name' => __( 'Updated Post Notification\'s Title', 'settings' ),
			'default' => 'Post Updated',
			'id' => 'update_notify_title_w4',
			'type' => 'text',
		) );


		$cmb->add_field( array(
			'name' => 'Basic Settings',
			'desc' => 'Change basic site settings',
			'type' => 'title',
			'id'   => 'wiki_basic_settings_title'
		) );

		$cmb->add_field( array(
			'name' => 'Enable Test Notifcation',
			'desc' => 'OneSignal notification will be sent only to the test users (if added).',
			'id'   => 'onesignal_test_notification',
			'type' => 'checkbox',
		) );

*/
		
	}

	public function admin_update_page(){
		$prefix = '_wordroid_update_v4_';


		$cmb = new_cmb2_box( array(
			'id'           => $prefix . 'wp-wordroid',
			'title'        => __( 'Update App', 'update' ),
			'object_types'  => array( 'options-page' ),
			'option_key'      => 'wordroid4-update', // The option key and admin menu page slug.
			'parent_slug'     => 'wordroid4-home', // Make options page a submenu item of
			'show_in_rest' => WP_REST_Server::READABLE,
			'context'      => 'normal',
			'priority'     => 'default',
		) );
		$cmb->add_field( array(
			'name' => 'Send an update Notification to your users',
			'desc' => 'This will show a dialog message to your users and asks them them to update the app.',
			'type' => 'title',
			'id'   => 'wordroid_update_title'
		) );
		$cmb->add_field( array(
			'name' => __( 'Update Message Title', 'config' ),
			'id' => 'update_title',
			'default' => 'New Update',
			'desc' => 'Max 50 characters',
			'type' => 'text',
		) );
		$cmb->add_field( array(
		    'name' => 'Update Message Body',
		    'desc' => 'What\'s new in this update ',
		    'id' => 'update_body',
		    'type' => 'textarea_small'
		) );
		$cmb->add_field( array(
			'name' => __( 'Version', 'config' ),
			'id' => 'version',
			'type'    => 'text_small',
		) );

		$cmb->add_field( array(
			'name' => 'Force Update',
			'desc' => 'Force users to update the app',
			'id'   => 'force_update',
			'type' => 'checkbox',
		) );
	}
	
	public function admin_app_defaults(){
		$prefix = '_wordroid_configure_app_defaults_';

		$cmb = new_cmb2_box( array(
			'id'           => $prefix . 'wp-wordroid',
			'title'        => __( 'Configuração', 'configure-app' ),
			'object_types'  => array( 'options-page' ),
			'option_key'      => 'wordroid4-configure-app-defaults', // The option key and admin menu page slug.
			'parent_slug'     => 'wordroid4-home', // Make options page a submenu item of
			'show_in_rest' => WP_REST_Server::READABLE,
			'context'      => 'normal',
			'priority'     => 'default',
		) );
/*

		$cmb->add_field( array(
			'name' => 'Hide Categories',
			'desc' => 'Categories you select here will not be shown in the app\'s category page. Note that the posts from that categories will still be shown in Latest,Search,Tag posts',
			'type' => 'title',
			'id'   => 'nav_hide_categories_v4'
		));

*/

		$cmb->add_field( array(
		    'name'              => 'Ocultar Categorias',
		    'desc'              => 'Selecione uma ou mais categorias que NÃO aparecerão no aplicativo',
		    'id'                => 'hide_category_multiple',
		    'taxonomy'          => 'category',
		    'type'              => 'pw_multiselect',
		    'select_all_button' => false,
		    'remove_default'    => 'true',
		    'options'           => $this->iweb_get_cmb_options_array_tax( 'category' ),
		    'attributes'        => array(
		        'placeholder' => ''
			),
		));
/*

		$cmb->add_field( array(
			'name' => 'Basic App Settings',
			'type' => 'title',
			'id'   => 'basic_app_settings'
		));
*/

/*
		$cmb->add_field( array(
			'name'    => 'Nome do Aplicativo',
			'description' => 'Será exibido na tela principal do aplicativo',
			'default' => get_bloginfo('name'),
			'id'      => 'app_title',
			'type'    => 'text',
		) );
*/

/*		

		$cmb->add_field( array(
			'name'    => 'Show App Intro for first time',
			'id'      => 'app_intro',
			'type'    => 'radio_inline',
			'options' => array(
				'1'   => __( 'Enable', 'cmb2' ),
				'0' => __( 'Disable', 'cmb2' ),
			),
			'default' => '0',
		) );
*/

/*

		$cmb->add_field( array(
			'name'    => 'Enable SignIn',
			'id'      => 'app_signin',
			'type'    => 'radio_inline',
			'options' => array(
				'1'   => __( 'Enable', 'cmb2' ),
				'0' => __( 'Disable', 'cmb2' ),
			),
			'default' => '0',
		) );

*/		

/*
		$cmb->add_field( array(
			'name'    => 'Página de Apresentação',
			'default' => get_bloginfo('url'),
			'id'      => 'about_page',
			'type'    => 'text',
		) );

		$cmb->add_field( array(
			'name'    => 'Página Política de Privacidade',
			'default' => get_bloginfo('url'),
			'id'      => 'privacy_page',
			'type'    => 'text',
		) );
*/
/*

		$cmb->add_field( array(
			'name' => 'Post Page Settings',
			'type' => 'title',
			'id'   => 'post_page_settings'
		));

*/

		$cmb->add_field( array(
			'name'    => 'Anúncios Banner',
			'id'      => 'banner_ads',
			'type'    => 'radio_inline',
			'options' => array(
				'1'   => __( 'Habilitado', 'cmb2' ),
				'0' => __( 'Desabilitado', 'cmb2' ),
			),
			'default' => '1',
		) );

		$cmb->add_field( array(
			'name'    => 'Anúncios Tela Cheia',
			'id'      => 'itn_ads',
			'type'    => 'radio_inline',
			'options' => array(
				'1'   => __( 'Habilitado', 'cmb2' ),
				'0' => __( 'Desabilitado', 'cmb2' ),
			),
			'default' => '1',
		) );

		$cmb->add_field( array(
			'name'    => 'Anúncios tela cheia Frequência',
			'desc'    => 'Nº de posts até o anúncio ser exibido',
			'default' => '7',
			'id'      => 'iaf',
			'type'    => 'text_small'
		) );

		$cmb->add_field( array(
			'name'    => 'Suporte a Fala',
			'id'      => 'post_speak_feature',
			'type'    => 'radio_inline',
			'options' => array(
				'1'   => __( 'Habilitado', 'cmb2' ),
				'0' => __( 'Desabilitado', 'cmb2' ),
			),
			'default' => '0',
		) );

		$cmb->add_field( array(
			'name'    => 'Copiar conteúdo',
			'desc'	  => 'Habilita ou Desabilita a cópia de conteúdo das postagens',
			'id'      => 'copy_feature',
			'type'    => 'radio_inline',
			'options' => array(
				'1'   => __( 'Habilitado', 'cmb2' ),
				'0' => __( 'Desabilitado', 'cmb2' ),
				
			),
			'default' => '1',
		) );


		$cmb->add_field( array(
			'name'    => 'Abrir Links Externos',
			'id'      => 'open_external_links',
			'type'    => 'radio_inline',
			'options' => array(
				'app' => __( 'Dentro do Aplicativo', 'cmb2' ),
				'chrome'   => __( 'Navegador Chrome ou outro navegador instalado', 'cmb2' ),
			//	'chooser'   => __( 'External browser (App chooser will be shown if multiple browsers are installed)', 'cmb2' ),
			),
			'default' => 'chrome',
		) );

/*

		$cmb->add_field( array(
			'name' => 'Posts-List Page Settings',
			'type' => 'title',
			'id'   => 'post_list_page_settings'
		));
*/		


		$cmb->add_field( array(
			'name'    => 'Banner no Rodapé',
			'id'      => 'list_banner_ads',
			'type'    => 'radio_inline',
			'options' => array(
				'1'   => __( 'Habilitado', 'cmb2' ),
				'0' => __( 'Desabilitado', 'cmb2' ),
			),
			'default' => '1',
		) );

		$cmb->add_field( array(
			'name'    => 'Anúncio Nativo',
			'id'      => 'list_native_ads',
			'type'    => 'radio_inline',
			'options' => array(
				'1'   => __( 'Habilitado', 'cmb2' ),
				'0' => __( 'Desabilitado', 'cmb2' ),
			),
			'default' => '1',
		) );

		$cmb->add_field( array(
			'name'    => 'Frequência Anúncio Nativo',
			'desc'    => 'Frequência de exibição do anúncio nativo',
			'default' => '7',
			'id'      => 'list_native_ads_freq',
			'type'    => 'text_small'
		) );
		
	}

	public function admin_configure_nav_drawer(){
		$prefix = '_wordroid_configure_app_';

		$cmb = new_cmb2_box( array(
			'id'           => $prefix . 'wp-wordroid',
			'title'        => __( 'MENU SUPERIOR', 'configure-app' ),
			'object_types'  => array( 'options-page' ),
			'option_key'      => 'wordroid4-configure-app', // The option key and admin menu page slug.
			'parent_slug'     => 'wordroid4-home', // Make options page a submenu item of
			'show_in_rest' => WP_REST_Server::READABLE,
			'context'      => 'normal',
			'priority'     => 'default',
		) );
		
/*		
		$cmb->add_field( array(
			'name' => 'Navgation Drawer Settings',
			'type' => 'title',
			'id'   => 'nav_settings'
		) );
*/
		$cmb->add_field( array(
			'name'    => 'Topo do Menu',
			'id'      => 'nav_header',
			'type'    => 'radio_inline',
			'options' => array(
				'0' => __( 'Desabilitado', 'cmb2' ),
				'1'   => __( 'Habilitado', 'cmb2' ),
			),
			'default' => '1',
		) );

/*

		$cmb->add_field( array(
			'name'    => 'Topo do Menu Cor de fundo',
			'id'      => 'nav_header_color',
			'type'    => 'colorpicker',
			'default' => '#1976D2',
			// 'options' => array(
			// 	'alpha' => true, // Make this a rgba color picker.
			// ),
		) );

*/



/*
		$cmb->add_field( array(
			'name'    => 'MENU SUPERIOR',
			'id'      => 'nav_visibility',
			'type'    => 'radio_inline',
			'options' => array(
				'0' => __( 'Desabilitado', 'cmb2' ),
				'1'   => __( 'Habilitado', 'cmb2' ),
			),
			'default' => '1',
		) );
*/		

/*		
		$cmb->add_field( array(
			'name' => 'Navigation Drawer Menu Items',
			'desc' => 'Add navigation drawer menu',
			'type' => 'title',
			'id'   => 'nav_menu_title'
		) );
*/
		

		$group_field_id = $cmb->add_field( array(
			'id'          => 'wiki_test_repeat_group',
			'type'        => 'group',
			//'description' => __( 'Generates reusable form entries', 'cmb2' ),
			// 'repeatable'  => false, // use false if you want non-repeatable group
			'options'     => array(
				'group_title'       => __( 'Item MENU SUPERIOR {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
				'add_button'        => __( 'ADICIONAR NOVO ITEM', 'cmb2' ),
				'remove_button'     => __( 'REMOVER ITEM', 'cmb2' ),
				'sortable'          => true,
				'closed'         => true, // true to have the groups closed by default
				//'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
			),
		) );

		// Id's for group's fields only need to be unique for the group. Prefix is not needed.
		$cmb->add_group_field( $group_field_id, array(
			'name' => 'Nome',
			'id'   => 'item_title',
			'type' => 'text',
			// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
		) );
		
		$cmb->add_group_field( $group_field_id, array(
			'name' => 'Icone',
			'desc' => 'FontAwesome sem o "<strong>fa-</strong>" ',
			'id'   => 'item_icon',
			'type' => 'text',
		) );
		
		$cmb->add_group_field( $group_field_id, array(
			'name' => 'Cor do Item',
			'id'   => 'item_color',
			'type'    => 'colorpicker',
		) );

		$cmb->add_group_field( $group_field_id, array(
			'name' => 'Info',
			'desc' => 'Informação a ser passada se necessário EX: nome_de_usuario_no_instagram',
			'id'   => 'item_data',
			'type' => 'text',
		) );
		
		$cmb->add_group_field( $group_field_id,array(
			'name'             => 'Opções',
			'desc'             => 'Item de Menu',
			'id'               => 'item_destination',
			'type'             => 'select',
			'show_option_none' => false,
			'options'          => array(
				'1' => __( 'Homepage', 'cmb2' ),
				'2'   => __( 'Tabbed Homepage', 'cmb2' ),
				'11'    => __( 'Lista de Posts', 'cmb2' ),
				'3'     => __( 'Lista de Categorias', 'cmb2' ),
				'4'     => __( 'Lista de Tag', 'cmb2' ),
				'7'     => __( 'Lista de Notificações', 'cmb2' ),
				'5'     => __( 'Postagens Salvas', 'cmb2' ),
			//	'8'     => __( 'About Page', 'cmb2' ),
			//	'9'     => __( 'PrivacyPolicy Page', 'cmb2' ),
				'10'     => __( 'Link WebView', 'cmb2' ),
				'16'     => __( 'Link Externo', 'cmb2' ),
				 '6'     => __( 'Youtube Canal', 'cmb2' ),
				'12'     => __( 'Facebook', 'cmb2' ),
				'13'     => __( 'Twitter', 'cmb2' ),
				'14'     => __( 'Instagram', 'cmb2' ),
				'15'     => __( 'Configurações', 'cmb2' ),
			),
		) );
		
	}

	function admin_option_bottom_menu_fields(){
		$prefix = '_wordroid_configure_bnav_';

		$cmb = new_cmb2_box( array(
			'id'           => $prefix . 'wp-wordroid',
			'title'        => __( 'MENU INFERIOR', 'configure-app' ),
			'object_types'  => array( 'options-page' ),
			'option_key'      => 'wordroid4-bottom-nav', // The option key and admin menu page slug.
			'parent_slug'     => 'wordroid4-home', // Make options page a submenu item of
			'show_in_rest' => WP_REST_Server::READABLE,
			'context'      => 'normal',
			'priority'     => 'default',
		) );
/*
		$cmb->add_field( array(
			'name' => 'Bottom Navgation Settings',
			'type' => 'title',
			'id'   => 'bnav_settings'
		) );
*/		

		$cmb->add_field( array(
			'name'    => 'Item ativo Cor',
			'id'      => 'selected_bnav_color',
			'type'    => 'colorpicker',
			'default' => '#ffffff',
			// 'options' => array(
			// 	'alpha' => true, // Make this a rgba color picker.
			// ),
		) );

		$cmb->add_field( array(
			'name'    => 'Item inativo Cor',
			'id'      => 'unselected_bnav_color',
			'type'    => 'colorpicker',
			'default' => '#000000',
			// 'options' => array(
			// 	'alpha' => true, // Make this a rgba color picker.
			// ),
		) );

		$cmb->add_field( array(
			'name'    => 'Cor de fundo',
			'id'      => 'back_bnav_color',
			'type'    => 'colorpicker',
			'default' => '#888888',
			// 'options' => array(
			// 	'alpha' => true, // Make this a rgba color picker.
			// ),
		) );

		$cmb->add_field( array(
			'name'             => '',
			'id'               => 'bnav_show_labels',
			'type'             => 'radio_inline',
			'options'          => array(
			//	'never' => __( 'Always Hidden', 'cmb2' ),
			//	'selected'   => __( 'Show when selected', 'cmb2' ),
				'always'     => __( '', 'cmb2' ),
			),
			'default'			=> 'always'
		) );

		$cmb->add_field( array(
			'name'             => 'Menu Inferior',
			'id'               => 'bnav_visibility',
			'type'             => 'radio_inline',
			'options'          => array(
				'1' => __( 'Visível', 'cmb2' ),
				'0'   => __( 'Oculto', 'cmb2' ),
			),
			'default'			=> '1'
		) );

		$cmb->add_field( array(
			'name' => 'Menu Inferior Itens',
			'desc' => '',
			'type' => 'title',
			'id'   => 'bnav_menu_title'
		) );
		$group_field_bnav = $cmb->add_field( array(
			'id'          => 'bottom_nav_group',
			'type'        => 'group',
			//'description' => __( 'Generates reusable form entries', 'cmb2' ),
			// 'repeatable'  => false, // use false if you want non-repeatable group
			'options'     => array(
				'group_title'       => __( ' Item MENU INFERIOR {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
				'add_button'        => __( 'ADICIONAR NOVO ITEM', 'cmb2' ),
				'remove_button'     => __( 'REMOVER ITEM', 'cmb2' ),
				'sortable'          => true,
				'closed'         => true, // true to have the groups closed by default
				//'remove_confirm' => esc_html__( 'Are you sure you want to remove ?', 'cmb2' ), // Performs confirmation before removing group.
			),
		) );

		// Id's for group's fields only need to be unique for the group. Prefix is not needed.
		$cmb->add_group_field( $group_field_bnav, array(
			'name' => 'Nome',
			'id'   => 'item_title',
			'type' => 'text',
			// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
		) );
		
		$cmb->add_group_field( $group_field_bnav, array(
			'name' => 'Icone',
			'desc' => 'FontAwesome sem o "<strong>fa-</strong>"',
			'id'   => 'item_icon',
			'type' => 'text',
		) );
		
		$cmb->add_group_field( $group_field_bnav, array(
			'name' => 'Cor do Item',
			'id'   => 'item_color',
			'type'    => 'colorpicker',
		) );

		$cmb->add_group_field( $group_field_bnav, array(
			'name' => 'Info',
			'desc' => 'Data that will be passed to the dstination. See the full list of data <a href="#">here</a>',
			'id'   => 'item_data',
			'type' => 'text',
		) );
		
		$cmb->add_group_field( $group_field_bnav,array(
			'name'             => 'Opções',
			'desc'             => 'item de Menu',
			'id'               => 'item_destination',
			'type'             => 'select',
			'show_option_none' => false,
			'default'          => 'custom',
			'options'          => array(
				'1' => __( 'Homepage', 'cmb2' ),
			    '2'     => __( 'Tabbed Homepage', 'cmb2' ),
				'11'    => __( 'Lista de Posts', 'cmb2' ),
				'3'     => __( 'Lista de Categorias', 'cmb2' ),
				'4'     => __( 'Lista de Tag', 'cmb2' ),
				'7'     => __( 'Lista de Notificações', 'cmb2' ),
				'5'     => __( 'Postagens Salvas', 'cmb2' ),
			//	'8'     => __( 'About Page', 'cmb2' ),
			//	'9'     => __( 'PrivacyPolicy Page', 'cmb2' ),
				'10'     => __( 'Link WebView', 'cmb2' ),
				'16'     => __( 'Link Externo', 'cmb2' ),
				 '6'     => __( 'Youtube Canal', 'cmb2' ),
			//	'12'     => __( 'Facebook', 'cmb2' ),
			//	'13'     => __( 'Twitter', 'cmb2' ),
			//	'14'     => __( 'Instagram', 'cmb2' ),
            //  '15'     => __( 'Configurações', 'cmb2' ),
			),
		) );
	}

	public function admin_option_menu_fields(){
		$prefix = '_wordroid4_config';

		$cmb = new_cmb2_box( array(
			'id'           => $prefix . 'wp-wordroid',
			'title'        => __( 'Homepage', 'config' ),
			'object_types'  => array( 'options-page' ),
			'option_key'      => 'wordroid4-config', // The option key and admin menu page slug.
			'parent_slug'     => 'wordroid4-home', // Make options page a submenu item of
			'show_in_rest' => WP_REST_Server::READABLE,
			'context'      => 'normal',
			'priority'     => 'default',
		) );
/*
		$cmb->add_field( array(
			'name' => 'Randomize Sections',
			'desc' => 'Shuffle the sections everytime the app opens',
			'id'   => 'randomize_section_checkbox',
			'type' => 'checkbox',
		) );
*/
/*
		$cmb->add_field( array(
			'name' => 'WorDroid Sections',
			'desc' => 'These sections will be used to show content on the WorDroid app homepage. Every section containt the <strong>Section Title</strong>, <strong>Layout Type *</strong>, <strong>Content Type *</strong>, <strong>Section Category/Tag *</strong>',
			'type' => 'title',
			'id'   => 'wiki_test_title'
		) );
	*/	

		$group_field_id = $cmb->add_field( array(
			'id'          => 'wordroid_section_group',
			'type'        => 'group',
			// 'repeatable'  => false, // use false if you want non-repeatable group
			'options'     => array(
				'group_title'   => __( 'BLOCO {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
				'add_button'    => __( 'Adicionar novo BLOCO', 'cmb2' ),
				'remove_button' => __( 'RemoveR BLOCO', 'cmb2' ),
				'sortable'      => true, // beta
				// 'closed'     => true, // true to have the groups closed by default
			),
		) );

		$cmb->add_group_field($group_field_id, array(
			'name'             => 'Conteúdo',
		//	'desc'             => 'Select the type of contents you want to show',
			'id'               => 'content_type',
			'type'             => 'select',
			'show_option_none' => true,
			'default'          => '1',
			'options'          => array(
				'1' => __( 'Postagens', 'cmb2' ),
				'3' => __( 'Categorias', 'cmb2' ),
				'4' => __( 'Tags', 'cmb2' ),
			),
		) );

		$cmb->add_group_field($group_field_id, array(
			'name'             => 'Layout',
		//	'desc'             => 'Select the layout type',
			'id'               => 'layout_type',
			'type'             => 'select',
			'show_option_none' => true,
			'default'          => '1',
			'options'          => array(
				'1' => __( 'Slider', 'cmb2' ),
				'4' => __( 'Horizontal Big Picture (Title inside)', 'cmb2' ),
				'3' => __( 'Horizontal Big Picture (Title outside)', 'cmb2' ),
				'2' => __( 'Grid', 'cmb2' ),
				'6' => __( 'List', 'cmb2' ),
			),
		) );

		$cmb->add_group_field( $group_field_id,array(
		    'name'              => 'Categoria(s) do Bloco',
       //   'desc'              => 'Select the category',
		    'id'                => 'category_multiple_choose',
		    'taxonomy'          => 'category',
		    'type'              => 'pw_multiselect',
		    'select_all_button' => false,
		    'remove_default'    => 'true',
		    'options'           => $this->iweb_get_cmb_options_array_tax( 'category' ),
		    'attributes'        => array(
		        'placeholder' => 'Choose the categories to show posts from'
		),
		));



		$cmb->add_group_field( $group_field_id,array(
			'name'           => 'Tags do Bloco',
		//	'desc'           => 'Select the tag you want to show posts from',
			'id'             => 'tag_multiple_select',
			'type'           => 'pw_multiselect',
			'taxonomy'       => 'post_tag', //Enter Taxonomy Slug
			'remove_default' => 'true',
			'options'           => $this->iweb_get_cmb_options_array_tax( 'post_tag' ), // Removes the default metabox provided by WP core.
			'attributes'        => array(
		        'placeholder' => 'Choose the tags to show posts from'
		),
		) );

		// Id's for group's fields only need to be unique for the group. Prefix is not needed.
		$cmb->add_group_field( $group_field_id, array(
			'name' => ' Nome ',
		//	'desc' => 'Title of your section',
			'id'   => 'title',
			'type' => 'text',
		) );
		
		$cmb->add_group_field($group_field_id, array(
			'name'             => 'Quantidade de Posts',
		//	'desc'             => 'No of posts to show in the section',
			'id'               => 'post_count',
			'type'             => 'select',
			'show_option_none' => true,
			'default'          => '10',
			'options'          => array(
				'1' => __( '1', 'cmb3' ),
				'2' => __( '2', 'cmb3' ),
				'3' => __( '3', 'cmb3' ),
				'4' => __( '4', 'cmb3' ),
				'5' => __( '5', 'cmb3' ),
				'6' => __( '6', 'cmb3' ),
				'7' => __( '7', 'cmb3' ),
				'8' => __( '8', 'cmb3' ),
				'9' => __( '9', 'cmb3' ),
				'10' => __( '10', 'cmb3' ),
			),
		) );
		$cmb->add_group_field($group_field_id, array(
			'name' => 'Conteúdo Aleatório',
			'desc' => 'Marque se quiser exibir conteúdo aleatório (Postagens,Categorias)',
			'id'   => 'randomize_content_checkbox',
			'type' => 'checkbox',
		) );

	}

	public function app_configuration_page(){
		$_prefix = "_wordroid4_app_configuration";

		$cmb = new_cmb2_box( array(
			'id'           => $prefix . 'wp-wordroid',
			'title'        => __( 'Configure App', 'config-app' ),
			'object_types'  => array( 'options-page' ),
			'option_key'      => 'wordroid4-app-configuration', // The option key and admin menu page slug.
			'parent_slug'     => 'wordroid4-app-configuration', // Make options page a submenu item of
			'show_in_rest' => WP_REST_Server::READABLE,
			'context'      => 'normal',
			'priority'     => 'default',
		) );


		$cmb->add_field( array(
			'name' => 'Banner Ad on post page',
			'desc' => 'field description (optional)',
			'id'   => 'enable_banner_ad_on_post',
			'type' => 'checkbox',
		) );


	}

	public function iweb_get_cmb_options_array_tax( $taxonomies, $query_args = '' ) {
	    $defaults = array(
	        'hide_empty' => false
	    );
	    $args = wp_parse_args( $query_args, $defaults );
	    $terms = get_terms( $taxonomies, $args );
	    $terms_array = array();
	    if ( ! empty( $terms ) ) {
	        foreach ( $terms as $term ) {
	            $terms_array[$term->term_id] = $term->name;
	        }
	    }
	    return $terms_array;
	}

	// Callback function
	public function show_cat_or_dog_options( $field ) {

		return array(
				'german-shepherd' => __( 'German Shepherd', 'cmb2' ),
				'bulldog'         => __( 'Bulldog', 'cmb2' ),
				'poodle'          => __( 'Poodle', 'cmb2' ),
			);
	}

	public function admin_option_activate_plugin(){
		$prefix ="";
		$cmb = new_cmb2_box( array(
			'id'           => $prefix . 'wp-wordroid4',
			'title'        => __( 'Activate Plugin', 'activate' ),
			'parent_slug'     => 'wordroid4-home', // Make options page a submenu item of
			'object_types'  => array( 'options-page' ),
			'option_key'      => 'wordroid4-plugin-activate', // The option key and admin menu page slug.
			'show_in_rest' => WP_REST_Server::READABLE,
			'context'      => 'normal',
			'priority'     => 'default',
		) );

		$cmb->add_field( array(
			'name' => 'Enter Codecanyon Item Purchase Code',
			'desc' => '<a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-">Get the Item Purchase Code from codecanyon</a> and paste it here',
			'type' => 'title',
			'id'   => 'wiki_test_title'
		) );

		$cmb->add_field( array(
			'name'    => 'Item Purchase Code',
			'desc'    => 'Enter the item purchase code',
			'id'      => 'ipc_code',
			'type'    => 'text',
			'sanitization_cb' => 'sanitize'
		) );

		$cmb->add_field( array(
			'name' => 'Manual Activation Code Code (Optional)',
			'desc' => 'If the Item Purchase Code activation is not working, then use it. You can <a href="mailto:anubhavanand884@gmail.com?subject=Manual Activation Code&body=My Purchase code is : \nMy Site URL is: \n">ask the author</a> for manual activation code.',
			'type' => 'title',
			'id'   => 'manual_activation_title'
		) );
		
		$cmb->add_field( array(
			'name' => 'Manual Activation Code ',
			'desc' => 'Use this Manual Activation Code field when the Item Purchase Code activation does not work. Leave it empty otherwise.',
			'id' => 'manual_activation_code',
			'type' => 'textarea',
		) );
	}



}
?>