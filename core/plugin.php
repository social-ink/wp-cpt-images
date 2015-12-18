<?php
//install
function cptImages_install() {
	
}


//links in plugins page
if ( $GLOBALS['pagenow'] == 'plugins.php' ) {
	add_filter( 'plugin_row_meta', 'cptImages_plugin_links', 10,2);
}			

function cptImages_plugin_links($links, $file) {
	if ( strpos($file, basename( __FILE__)) === false ) {
		return $links;
	}
  
	$plugin = plugin_basename(__FILE__);

	$links[] = '<a href="tools.php?page=cptImages" title="cptImages Settings">Settings</a>';
	$links[] = '<a href="https://www.social-ink.net" title="Visit Social Ink">Visit Social Ink</a>';
	
	return $links;
}	