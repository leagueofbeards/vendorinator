<?php
namespace Habari;

class VendorInator extends Plugin
{
	public function action_init() {
		DB::register_table('vendors');
		$this->ad_pages();
	}

	public function action_plugin_activation( $plugin_file ) {
		Post::add_new_type( 'vendor' );
		$this->create_ad_vendors_table();
	}
	
	public function action_plugin_deactivation ( $file='' ) {
		Post::deactivate_post_type( 'vendor' );
	}

	private function create_ad_vendors_table() {
		$sql = "CREATE TABLE {\$prefix}vendors (
			id int unsigned NOT NULL AUTO_INCREMENT,
			post_id int unsigned NOT NULL,
			contact_name varchar(255) NULL,
			contact_email varchar(255) NULL,
			state int unsigned NOT NULL,
			plan_id int unsigned NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `post_id` (`post_id`)			
			) DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;";

		DB::dbdelta( $sql );
	}

	private function ad_pages() {
		$this->add_template('vendors', dirname($this->get_file()) . '/admin/vendors.php');
		$this->add_template('add_vendor', dirname($this->get_file()) . '/admin/add_vendor.php');
	}

	public function filter_admin_access_tokens( array $require_any, $page ) {
		switch ($page) {
			case 'vendors' :
				$require_any = array('post_entry' => true);
			break;
			case 'add_vendor' :
				$require_any = array('post_entry' => true);
			break;			
		}
		
		return $require_any;
	}

	public function filter_post_type_display($type, $g_number)	{
		switch($type) {
			case 'vendor':
				switch($g_number) {
					case 'singular':
						return _t('Vendor');
					case 'plural':
						return _t('Vendor');
				}
				break;
		}
		return $type;
	}

	public function filter_posts_get_paramarray($paramarray) {
		$queried_types = Posts::extract_param($paramarray, 'content_type');
		if($queried_types && in_array('vendor', $queried_types)) {
			$paramarray['post_join'][] = '{vendors}';
			$default_fields = isset($paramarray['default_fields']) ? $paramarray['default_fields'] : array();
			$default_fields['{vendors}.contact_name'] = '';
			$default_fields['{vendors}.contact_email'] = '';
			$default_fields['{vendors}.state'] = '';
			$default_fields['{vendors}.plan_id'] = '';
			$paramarray['default_fields'] = $default_fields;
		}
		
		return $paramarray;
	}
	
	public function filter_post_get($out, $name, $vendor) {
		if('vendor' == Post::type_name($vendor->get_raw_field('content_type'))) {
			switch($name) {
			}
		}
		
		return $out;
	}
	
	public function filter_post_schema_map_vendor($schema, $post) {
		$schema['vendors'] = $schema['*'];
		// Store the id of the post in the post_id field of the invoices table
		$schema['vendors']['post_id'] = '*id';
		return $schema;
	}
	
	public function action_auth_ajax_add_vendor($data) {
		$user = User::identify();
		$vars = $data->handler_vars;
				
		if( $vars['active'] == 'yes' ) {
			$active = 1;
		} else {
			$active = 0;
		}

		$postdata= array(
			'title' 		=>	$vars['title'],
			'slug'			=>	Utils::slugify( $vars['title'] ),
			'content'		=>	$vars['title'],
			'status'		=>	$active,
			'contact_name'	=>	$vars['contact_name'],
			'contact_email'	=>	$vars['contact_email'],
			'plan_id'		=>	$vars['plan'],
			'user_id'		=>	$user->id,	
			'pubdate'		=>	DateTime::date_create( date(DATE_RFC822) ),
			'status'		=>	Post::status( 'published' ),
			'content_type'	=> Post::type('vendor'),
		);
		
		$vendor = Vendor::create( $postdata );
		
		Utils::redirect( Site::get_url('admin') . '/add_vendor?id=' . $vendor->id );
	}
	
	public function action_auth_ajax_update_vendor($data) {
		$user = User::identify();
		$vars = $data->handler_vars;
		
		$vendor = Vendor::get( array('id' => $vars['vendor_id']) );
		
		if( $vars['active'] == 'yes' ) {
			$active = 1;
		} else {
			$active = 0;
		}

		$postdata= array(
			'title' 		=>	$vars['title'],
			'slug'			=>	Utils::slugify( $vars['title'] ),
			'content'		=>	$vars['title'],
			'state'		=>	$active,
			'contact_name'	=>	$vars['contact_name'],
			'contact_email'	=>	$vars['contact_email'],
			'plan_id'		=>	$vars['plan'],
			'updated'		=>	DateTime::date_create( date(DATE_RFC822) ),
		);
		
		foreach( $postdata as $key => $value ) {
			$vendor->$key = $value;
		}

		$vendor->update();
		
		Utils::redirect( Site::get_url('admin') . '/add_vendor?id=' . $vendor->id );
	}
}
?>