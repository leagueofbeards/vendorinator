<?php
namespace Habari;

class VendorInator extends Plugin
{
	public function action_init() {
		DB::register_table('ad_vendors');
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
		$sql = "CREATE TABLE {\$prefix}ad_vendors (
			id int unsigned NOT NULL AUTO_INCREMENT,
			post_id int unsigned NOT NULL,
			name varchar(255) NULL,
			contact_name varchar(255) NULL,
			contact_email varchar(255) NULL,
			status int unsigned NOT NULL,
			plan_id int unsigned NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `post_id` (`post_id`)			
			) DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;";

		DB::dbdelta( $sql );
	}

	private function ad_pages() {
		$this->add_template('vendors', dirname($this->get_file()) . '/admin/vendors.php');
	}

	public function filter_admin_access_tokens( array $require_any, $page ) {
		switch ($page) {
			case 'vendors' :
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
			$default_fields['{vendors}.location_id'] = 0;
			$default_fields['{vendors}.active'] = '';
			$default_fields['{vendors}.date'] = '';
			$default_fields['{vendors}.link'] = '';
			$default_fields['{vendors}.image_url'] = '';
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
}
?>