<?php
	
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}
 
$gravitation_portfolios_cat = 'gravitation_portfolios_cat';
$portfolios_home_active = 'portfolios_home_active';


 
delete_option( $gravitation_portfolios_cat );

 
// For site options in Multisite
//delete_site_option( $option_name );  
 
// Drop a custom db table
//global $wpdb;
//$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}mytable" );

?>