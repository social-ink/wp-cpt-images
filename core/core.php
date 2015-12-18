<?php
// cpts
function get_cpts_images() {
	$allCPTs = get_post_types();
	$skipTypes = get_skip_cpts();
	foreach($allCPTs as $slug => $slug)
		if(in_array($slug,$skipTypes))
			unset($allCPTs[$slug]);

	return $allCPTs;
}
function get_skip_cpts() {
	return explode(',',cpts_to_skip);
}

//file saving
function detach_cpt_image($cpt_slug) {
	$connections = get_cpt_connections();
	if(array_key_exists($cpt_slug,$connections))
		unset($connections[$cpt_slug]);
	update_cpt_connections($connections);
}

function get_cpt_image($cpt_slug) {
	$links = get_cpt_connections();
	$img = array_key_exists($cpt_slug,$links) ? $links[$cpt_slug] : false;
	if($img)
		return wp_get_attachment_image_src($img);
}
function get_cpt_connections() {
	$connections = get_option(cptplugin_connection_option);
	if($connections) {
		$connections = json_decode($connections, true);
		foreach($connections as $slug => $img_id) {
			if(!wp_get_attachment_image_src($img_id))
				unset($connections[$slug]);
		}
		update_cpt_connections($connections);
	}
	return $connections ? $connections : array();
}
function update_cpt_connections($connection_array) {
	$connections = !empty($connection_array) ? json_encode($connection_array) : array();
	//var_dump($connections);
	return update_option(cptplugin_connection_option, $connections);
}
function update_cpt_connection_link($cpt_name, $val) {
	$connections = get_cpt_connections();
	$connections[$cpt_name] = $val;
	return update_option(cptplugin_connection_option, json_encode($connections));
}

function cptImages_savefile($file, $name, $slug = false, $parent_post_id = 0, $content = '', $attachData = array()) {
	if(empty($file))
		return false;
	if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
	$upload_overrides = array( 'test_form' => false );
	$movefile = wp_handle_upload( $file, $upload_overrides );
	$name = 'wp-cpt-images image for ' . $name;

	if ( $movefile && !isset($movefile['error'] )) {
		$wp_filetype = $movefile['type'];
		$filename = $movefile['file'];
		$wp_upload_dir = wp_upload_dir();
		$attachment = array(
			'guid' => $wp_upload_dir['url'] . '/' . basename( $filename ),
			'post_mime_type' => $wp_filetype,
			'post_title' => $name,
			'post_content' => $content,
			'post_status' => 'inherit'
		);
		$attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id);
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		add_post_meta($attach_id, 'wp-cpt-image-attachment',$slug);
		foreach($attachData as $key => $val)
			add_post_meta($attach_id, $key,$val);
			
		update_cpt_connection_link($slug, $attach_id);
	}
	return $attach_id;
}


//main page
add_shortcode('cptImage', 'cptimage_output');	

function cptimage_output($atts=null) {
	extract(shortcode_atts(array(	//get the attributes if any
		'cpt' => ''
	), $atts)); 	

	cptimage_image($cpt);
}

// @displays full image
function cptimage_image($current_CPT=null) {
	$image_url=get_cptimage_image();
	if($image_url)
		echo "<img class=\"cpt_archive_image\" id=\"cpt_archive_image_$current_CPT\" src=\"$image_url\" alt=\"$current_CPT\" />";
}

// @gets image url; if argument is true it will print on screen, otherwise return it
function get_cptimage_image($current_CPT=null, $echo_url = false) {

	if(empty($current_CPT))		
		if(is_post_type_archive())  {
			$current_CPT = get_queried_object();
			$current_CPT = $current_CPT->name;
		}
		elseif(is_single()) {
			global $post;
			$current_CPT=get_post_type($post);
		}
		elseif(is_category()) {
			$current_CPT='post';
		}
		
		if($current_CPT) {
			$img = get_cpt_image($current_CPT);
		} 
		return !empty($img) ? reset($img) : false;

}