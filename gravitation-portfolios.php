<?php
/*
Plugin Name: Gravitation Portfolios
Plugin URI: https://github.com/UlisesFreitas/gravitation-portfolios
Description: Gravitation portfolios, is a plugin to display portfolios on your site, with a page template, Quicksand integrated fully responsive and sortable
Author: Ulises Freitas
Version: 1.0.1
Author URI: https://disenialia.com/
License: GPLv2
*/
/*-----------------------------------------------------------------------------*/
/*
	Gravitation portfolios
    Copyright (C) 2016 Gravitation portfolios

    This library is free software; you can redistribute it and/or
    modify it under the terms of the GNU Lesser General Public
    License as published by the Free Software Foundation; either
    version 2.1 of the License, or (at your option) any later version.

    This library is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
    Lesser General Public License for more details.

    You should have received a copy of the GNU Lesser General Public
    License along with this library; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301
    USA


	Disenialia©, hereby disclaims all copyright interest in the
	library Gravitation portfolios (a library for display portfolios on Wordpress) 
	written by Ulises Freitas.
	
	Disenialia©, 12 May 2016
	CEO Ulises Freitas.
*/
/*-----------------------------------------------------------------------------*/
 
    
function gravitation_portfolios_install() {
 
    // Trigger our function that registers the custom post type
    gravitation_portfolios_create_post_type();
    
 
    // Clear the permalinks after the post type has been registered
    flush_rewrite_rules();
 
}
register_activation_hook( __FILE__, 'gravitation_portfolios_install' );

function gravitation_portfolios_deactivation() {
 
    // Our post type will be automatically removed, so no need to unregister it
 
    // Clear the permalinks to remove our post type's rules
    flush_rewrite_rules();
 
}
register_deactivation_hook( __FILE__, 'gravitation_portfolios_deactivation' );

add_filter( 'image_size_names_choose', 'gravitaion_portfolios_thumbnail' );
 
function gravitaion_portfolios_thumbnail( $sizes ) {
    return array_merge( $sizes, array(
        'gravitaion-portfolios-thumbnail' => __( 'Portfolio thumbnail' ),
    ) );
}

add_action('init','gravitaion_portfolios_img_size');
function gravitaion_portfolios_img_size(){
	add_image_size( 'gravitaion-portfolios-thumbnail', 340, 340, array( 'center', 'center' ) ); 
}

add_action( 'plugins_loaded', 'gravitation_portfolios_load_textdomain' );
 
/**
 * Load plugin textdomain.
 */
function gravitation_portfolios_load_textdomain() {
  load_plugin_textdomain( 'gravitation_portfolios', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

add_filter('widget_text', 'do_shortcode');
function custom_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

require_once('Portfolios.php');
add_action( 'plugins_loaded', array( 'Portfolios', 'get_instance' ) );

function get_gravitation_portfolios_single_template($single_template) {
     global $post;

     if ($post->post_type == 'gv_portfolios') {
          $single_template = plugin_dir_path(__FILE__). 'single-gravitation-portfolio.php';
     }
     return $single_template;
}
add_filter( 'single_template', 'get_gravitation_portfolios_single_template' );

function get_gravitation_portfolios_archive_template($archive_template) {
     global $post;
     if ($post->post_type == 'gv_portfolios') {
          $archive_template = plugin_dir_path(__FILE__) . '/archive-gravitation-portfolio.php';
     }
     return $archive_template;
}
add_filter( "archive_template", "get_gravitation_portfolios_archive_template" ) ;

/*
function gravitation_portfolios_bootstrap() {
	$check_portfolio_bootstrap = get_option('portfolio_bootstrap');
	    if($check_portfolio_bootstrap == 1):
			$handle = 'bootstrap.min.css';
			if (wp_style_is( $handle, $list = 'enqueued, to_do, registered' )) {
				return;
	     	} else {
		 		wp_enqueue_style( 'gravitation_portfolios_bootstrap', plugins_url( 'bootstrap/css/bootstrap.min.css', __FILE__ ), array(), '3.3.5', true  );
		 		
	     	}
	 	endif;
}
add_action( 'wp_enqueue_scripts', 'gravitation_portfolios_bootstrap' );
*/

function gravitation_portfolios_stylesheet() {
	 	
	 	wp_enqueue_style( 'gravitation_portfolios_style', plugins_url( 'css/portfolio-styles.css', __FILE__ ), array(), '1.0.0', false );
	 	
}
add_action( 'wp_enqueue_scripts', 'gravitation_portfolios_stylesheet' );

/*
function gravitation_portfolios_bootstrapjs(){
		   
		$check_portfolio_bootstrap = get_option('portfolio_bootstrap');
	    if($check_portfolio_bootstrap == 1):
		    $handle = 'bootstrap.min.js';
			$list = 'enqueued';
			if (wp_script_is( $handle, $list )) {
				return;
	     	} else {
		 		 wp_register_script('gravitation_portfolios_bootstrap_js', plugin_dir_url( __FILE__ ).'bootstrap/js/bootstrap.min.js');
		 		 wp_enqueue_script('gravitation_portfolios_bootstrap_js', array('jquery'), '3.3.6', true );
	     	}
     	endif;
     	
}
add_action('wp_enqueue_scripts','gravitation_portfolios_bootstrapjs',160);
*/

function gravitation_portfolios_scripts(){
	 	
	 	wp_enqueue_script('gravitation_portfolios_easing', plugin_dir_url( __FILE__ ).'js/jquery.easing.1.3.js', array('jquery'), '20150212', true  );
	 	wp_enqueue_script('gravitation_portfolios_quicksand', plugin_dir_url( __FILE__ ).'js/jquery.quicksand.js', array('jquery'), '20150212', true  );
     	wp_enqueue_script('gravitation_portfolios_functions', plugin_dir_url( __FILE__ ).'js/functions.js', array('jquery'), '20150212', true  );
}
add_action('wp_enqueue_scripts','gravitation_portfolios_scripts',160);

function gravitation_set_custom_edit_portfolios_columns($columns) {
    unset( $columns['author'] );
    unset( $columns['date'] );
    
    $columns['gravitation_portfolios_client'] = __( 'Company', 'gravitation_portfolios' );
    $columns['gravitation_portfolios_website'] = __( 'Website', 'gravitation_portfolios' );
    $columns['gravitation_portfolios_shortcode'] = __( 'Shortcode', 'gravitation_portfolios' );
    $columns['date'] = __( 'Date','gravitation_portfolios' );

    return $columns;
}
add_filter( 'manage_gv_portfolios_posts_columns', 'gravitation_set_custom_edit_portfolios_columns' );

function gravitation_custom_portfolios_column( $column, $post_id ) {
    switch ( $column ) {

        
        case 'gravitation_portfolios_client':
        	$meta_company = get_post_meta( get_the_ID(), '_portfolios_post_client', true );
			
         	echo $meta_company;

        break;
        
        case 'gravitation_portfolios_website':
	        $meta_website = get_post_meta( get_the_ID(), '_portfolios_post_website', true );
	        echo '<a href="' . $meta_website . '" tsrget="_blank" rel="nofollow">' . $meta_website . '</a>';
        break;

        case 'gravitation_portfolios_shortcode' :
        	echo '[gravitation_portfolios ids="' . $post_id . '"]';
        break;
        
        

    }
}
add_action( 'manage_gv_portfolios_posts_custom_column' , 'gravitation_custom_portfolios_column', 10, 2 );


function custom_pagination($numpages = '', $pagerange = '', $paged='') {

  if (empty($pagerange)) {
    $pagerange = 2;
  }

  /**
   * This first part of our function is a fallback
   * for custom pagination inside a regular loop that
   * uses the global $paged and global $wp_query variables.
   * 
   * It's good because we can now override default pagination
   * in our theme, and use this function in default quries
   * and custom queries.
   */
  global $paged;
  if (empty($paged)) {
    $paged = 1;
  }
  if ($numpages == '') {
    global $wp_query;
    $numpages = $wp_query->max_num_pages;
    if(!$numpages) {
        $numpages = 1;
    }
  }

  /** 
   * We construct the pagination arguments to enter into our paginate_links
   * function. 
   */
  $pagination_args = array(
    'base'            => get_pagenum_link(1) . '%_%',
    'format'          => 'page/%#%',
    'total'           => $numpages,
    'current'         => $paged,
    'show_all'        => False,
    'end_size'        => 1,
    'mid_size'        => $pagerange,
    'prev_next'       => True,
    'prev_text'       => __('&laquo;'),
    'next_text'       => __('&raquo;'),
    'type'            => 'plain',
    'add_args'        => false,
    'add_fragment'    => ''
  );

  $paginate_links = paginate_links($pagination_args);

  if ($paginate_links) {
    echo '<div class="col-md-12 text-center"><nav class="custom-pagination">';
      echo '<span class="page-numbers page-num">'. __('Page','gravitation_portfolios'). $paged . ' of ' . $numpages . '</span> ';
      echo $paginate_links;
    echo '</nav></div>';
  }

}

function gravitation_portfolios_shortcode($atts, $content=null){
   
    extract(shortcode_atts(array(
	    'ids' => '',
	    'category' => '',
		'count' => '3',
		'order' => 'DESC',
		'orderby' => 'menu_order',
        
    ), $atts)); 
	
	$args = array();
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	//All portfolios [gravitation_portfolios]
	if(!$ids){
		$args=array(
			
			'post_type' => 'gv_portfolios',
			'posts_per_page' => intval($count),
			'paged' => $paged,
			'post_status'=> 'publish',
			'order' => $order,
			'orderby' => $orderby,
			
		);
	}
	
	//portfolios ids [gravitation_portfolios ids="1,2,3,5"]
	if( $ids && !$category ){
		$cids = explode(',', $ids);
		$aids = array();
		foreach($cids as $key => $value){	
			$aids[] = $value;
		}
		$count = count($cids);
		$args['post__in'] = implode(',', $aids);
		
		$args=array(
			
			'post_type' => 'gv_portfolios',
			'post__in' => $aids,
			'posts_per_page' => intval($count),
			'paged' => $paged,
			'post_status'=> 'publish',
			'order' => $order,
			'orderby' => $orderby,
		);
	}
	
	//portfolios ids [gravitation_portfolios ids="1,2,3,5" category="customers"]
	if( $ids && $category ){
		$cids = explode(',', $ids);
		$aids = array();
		foreach($cids as $key => $value){	
			$aids[] = $value;
		}
		$count = count($cids);
		$args['post__in'] = implode(',', $aids);
		
		$args=array(
			
			'post_type' => 'gv_portfolios',
			'post__in' => $aids,
			'posts_per_page' => intval($count),
			'order' => $order,
			'orderby' => $orderby,
			'paged' => $paged,
			'post_status'=> 'publish',
			'tax_query' => array(
			'relation' => 'OR',
				array(
					'taxonomy' => 'gravitation_portfolios_cat',
					'field'    => 'slug',
					'terms'    => array( $atts['category'] ),
				),
			),
		);
	}
	
	//portfolios ids [gravitation_portfolios count="3"]
	if( $count && !$category ){
		
		$args=array(
			
			'post_type' => 'gv_portfolios',
			'posts_per_page' => intval($count),
			'paged' => $paged,
			'post_status'=> 'publish',
			'order' => $order,
			'orderby' => $orderby,
			
		);
	}
	
	//portfolios ids [gravitation_portfolios count="3" category="customers"]
	if( $count && $category ){
		
		
		$args=array(
			
			'post_type' => 'gv_portfolios',
			'posts_per_page' => intval($count),
			'order' => $order,
			'orderby' => $orderby,
			'paged' => $paged,
			'post_status'=> 'publish',
			'tax_query' => array(
			'relation' => 'OR',
				array(
					'taxonomy' => 'gravitation_portfolios_cat',
					'field'    => 'slug',
					'terms'    => array( $atts['category'] ),
				),
			),
		);
	}
	
	//portfolios ids [gravitation_portfolios category="customers"]
	if( !$count && $category ){
		
		
		$args=array(
			
			'post_type' => 'gv_portfolios',
			'order' => $order,
			'orderby' => $orderby,
			'paged' => $paged,
			'post_status'=> 'publish',
			'tax_query' => array(
			'relation' => 'OR',
				array(
					'taxonomy' => 'gravitation_portfolios_cat',
					'field'    => 'slug',
					'terms'    => array( $atts['category'] ),
				),
			),
		);
	}
	
	
	$query = new WP_Query($args);
	
	if(!$count){
		$count = $query->post_count;
	}

	$html = '';

    if ($query->have_posts()){ ?>
		<div class="container">
			
			<div class="col-md-12"> 
		    	<div class="text-center">
			            <ul id="filterOptions" class="filterOptions visible-lg visible-md visible-sm">
							<li class="active"><a href="#" class="all"><?php _e('All','gravitation_portfolios');?></a></li>
							<?php				
								
								$terms = get_terms('gravitation_portfolios_cat', $args);
								$count = count($terms); 
								$i=0;
								$term_list = '';
								if ($count > 0) {
									foreach ($terms as $term) {
										$i++;
										if($term->count !=  0){
											$term_list .= '<li><a href="#" class="portfolio-'. $term->slug .'">' . $term->name . ' <span></span></a></li>';
										}
										// if count is equal to i then output blank
										if ($count != $i){
											$term_list .= '';
										}else{
											$term_list .= '';
										}
									}
									echo $term_list;
								}
							?>
						</ul>
		       		</div>
		    </div>
		</div>
		<div class="col-md-12">
			<ul class="ourHolder list-unstyled list-inline grid">
				<?php 
					while ( $query->have_posts() ) : $query->the_post(); 

					$terms = get_the_terms( get_the_ID(), 'gravitation_portfolios_cat' ); 
					
					$large_image =  wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full', false, '' ); 
					$large_image = $large_image[0]; 
					
					?>
					
					<li class="item col-md-4" data-id="id-<?php echo get_the_ID(); ?>" data-type="portfolio-<?php foreach ($terms as $term) { echo $term->slug; } ?>">
					
					<a href="<?php echo get_permalink(get_the_ID()); ?>">
					<figure class="effect-layla">
						<?php if(!empty($large_image)):?>
							<img src="<?php echo $large_image; ?>" alt="" class="img-responsive">
						<?php else: ?>
							<img src="<?php echo plugins_url().'/gravitation-portfolio/images/set_a_thumbnail.png';?>" alt="">
						<?php endif; ?>
						<figcaption>
							
							<h3><?php the_title(); ?></h3>
							<p><?php //the_excerpt(); ?></p>
							<!--a href="<?php echo get_permalink(get_the_ID()); ?>"><?php echo __('View More','gravitation-portfolios'); ?></a-->
						</figcaption>			
					</figure>
					</a>
					</li>
					
					
				
					<?php endwhile; ?>	
		</ul>
				<div class="clearfix"></div>
				
				<!-- pagination here -->
				    <?php
				      if (function_exists('custom_pagination')) {
				        custom_pagination($query->max_num_pages,"",$paged);
				      }
				    ?>
				   
		</div>
		<?php
	wp_reset_query();
	
	}
    
}
add_shortcode('gravitation_portfolios', 'gravitation_portfolios_shortcode');    		

add_action('admin_menu' , 'gravitation_portfolios_help_admin_menu'); 
function gravitation_portfolios_help_admin_menu() {
    add_submenu_page('edit.php?post_type=gv_portfolios', __('Help', 'gravitation_portfolios'), __('Help', 'gravitation_portfolios'), 'administrator', basename(__FILE__), 'gravitation_portfolios_help_page');	
}


function gravitation_portfolios_help_page() {

		if(isset($_REQUEST['update_gravitation_portfolios_settings'])){ 
				if ( !isset($_POST['gravitation_portfolios_nonce']) || !wp_verify_nonce($_POST['gravitation_portfolios_nonce'],'gravitation_portfolios_settings') ){
				    _e('Sorry, your nonce did not verify.', 'gravitation_portfolios');
				   exit;
				}else{
					
				  	//update_option('portfolio_bootstrap',$_POST['portfolio_bootstrap']);
				  	update_option('portfolios_count',$_POST['portfolios_count']);
				  
				}
			}
		?>
		<div id="custom-branding-general" class="wrap">
				
				<h2><?php esc_html_e('Help GV. portfolios','gravitation_portfolios'); ?></h2>
				
			<div class="metabox-holder">
				<div class="postbox">
				<div class="inside">
					
					<form method="post" action="edit.php?post_type=gv_portfolios&page=gravitation-portfolios.php">
					<?php settings_fields( 'gravitation-portfolios-settings-group' ); ?>
					<?php do_settings_sections( 'gravitation-portfolios-settings-group' ); ?>
				    <table class="form-table">
				        <tr valign="top">
				       
				        <!--th scope="row"><?php _e('Include Bootstrap?','gravitation_portfolios'); ?>
					        <div class="sidebar-description">
								<p class="description"><?php _e('If your theme already includes bootstrap set this option to NO.','gravitation_portfolios'); ?></p>
							</div>
						</th-->
				        
				        <!--td>
				        <select name="portfolio_bootstrap">
							<?php
								$check_portfolio_bootstrap = get_option('portfolio_bootstrap');
								for($i=0;$i<2;$i++){
									if($i == 0){
										$yes_no = __('No','gravitation_portfolios');
									}else{
										$yes_no = __('Yes','gravitation_portfolios');
									}
									echo '<option value="'.$i.'"'.selected($check_portfolio_bootstrap, $i, false).'>'.$yes_no.'</option>';	 
								}		 
							?>										
						</select>
				        </td-->
				        </tr>
				        <tr valign="top">
				        <th scope="row"><?php _e('Portfolios order','gravitation_portfolios'); ?></th>
				        <td>
				        <select name="portfolio_order">
							<?php
								$check_portfolio_order = get_option('portfolio_order');
								for($i=0;$i<2;$i++){
									if($i == 0){
										$portfolios_order = __('Older first','gravitation_portfolios');
									}else{
										$portfolios_order = __('Latest first','gravitation_portfolios');
									}
									echo '<option value="'.$i.'"'.selected($check_portfolio_order, $i, false).'>'.$portfolios_order.'</option>';	 
								}		 
							?>										
						</select>
				        </td>
				        </tr>
				        <tr valign="top">
				        <th scope="row"><?php echo __('Number of portfolios to show','gravitation_portfolios'); ?></th>
				        <td><input type="number" name="portfolios_count" value="<?php echo esc_attr( get_option('portfolios_count') ); ?>" /></td>
				        </tr>
				        
				    </table>
    
					<?php wp_nonce_field( 'gravitation_portfolios_settings', 'gravitation_portfolios_nonce' ); ?>
				    <p class="submit">
				        <input class="button-primary" type="submit" name="update_gravitation_portfolios_settings" value="<?php _e( 'Save Settings', 'gravitation_portfolios' ) ?>" />
				    </p> 
					</form>
					
					<p><?php _e('For Gravitation portfolios to work you have to create a portfolio Category then create a portfolio over Add New portfolio','gravitation_portfolios'); ?></p>
					<p>To display portfolios, two ways to do it, </p>
						<ol>
							<li>create a page under Add New Page name it as you like and select "Show All Portfolios" on page Template. </li>
							<li>By shortcodes</li>
						</ol>
					<hr>
					<p><?php _e('Type of shortcodes:','gravitation_portfolios'); ?></p>
					<p><?php _e('Pages and Posts','gravitation_portfolios'); ?></p>
					
					<p><?php _e('Show all portfolios: <strong>[gravitation_portfolios]</strong>','gravitation_portfolios'); ?></p>
					
					<p><?php _e('Show "x" portfolios: <strong>[gravitation_portfolios count="x"]</strong> ,where "x" is a number <strong>[gravitation_portfolios count="3"]</strong>','gravitation_portfolios'); ?></p>
					
					<p><?php _e('Show all portfolios of one "category" : <strong>[gravitation_portfolios category="customers"]</strong> ,where "customers" is a category created on portfolios category','gravitation_portfolios'); ?></p>
					<p><?php _e('Combined show "x" portfolios of one "category" : <strong>[gravitation_portfolios count="x" category="customers"]</strong> ,where "x" is a number and "customers" is a category created on portfolio category','gravitation_portfolios'); ?></p>
					
					<ol>
						<li><strong>[gravitation_portfolios]</strong> Display All portfolios</li>
						<li><strong>[gravitation_portfolios count="3"]</strong> Display 3 portfolios of the selected category on Home page</li>
						<li><strong>[gravitation_portfolios category="customers"]</strong> Display All portfolios of "Customers"</li>
						<li><strong>[gravitation_portfolios count="2" category="customers"]</strong> Display 2 portfolios of "Customers" Category</li>
						<li><strong>[gravitation_portfolios category="customers" ids="1,3,6"]</strong> Display All selected "ids" portfolios of "Customers"</li>
					</ol>
            
    			</div>
  			</div>
		</div>
		</div>
		<?php 
}
	
if( ! function_exists( 'gravitation_portfolios_create_post_type' ) ) :
	
	function gravitation_portfolios_create_post_type() {
		
		$labels = array(
		'name'                => _x( 'GV. portfolios', 'Post Type General Name', 'gravitation_portfolios' ),
		'singular_name'       => _x( 'portfolios', 'Post Type Singular Name', 'gravitation_portfolios' ),
		'menu_name'           => __( 'GV. portfolios', 'gravitation_portfolios' ),
		'name_admin_bar'      => __( 'GV. portfolios', 'gravitation_portfolios' ),
		'parent_item_colon'   => __( 'Parent portfolio:', 'gravitation_portfolios' ),
		'all_items'           => __( 'All portfolios', 'gravitation_portfolios' ),
		'add_new_item'        => __( 'Add portfolio', 'gravitation_portfolios' ),
		'add_new'             => __( 'Add New', 'gravitation_portfolios' ),
		'new_item'            => __( 'New portfolio', 'gravitation_portfolios' ),
		'edit_item'           => __( 'Edit portfolio', 'gravitation_portfolios' ),
		'update_item'         => __( 'Update portfolio', 'gravitation_portfolios' ),
		'view_item'           => __( 'View portfolio', 'gravitation_portfolios' ),
		'search_items'        => __( 'Search portfolio', 'gravitation_portfolios' ),
		'not_found'           => __( 'portfolios Not found', 'gravitation_portfolios' ),
		'not_found_in_trash'  => __( 'portfolios Not found in Trash', 'gravitation_portfolios' ),
	);
	
	$args = array(
		'label'               => __( 'GV. Portfolios', 'gravitation_portfolios' ),
		'description'         => __( 'GV. Portfolios Creator simple responsive portfolios items', 'gravitation_portfolios' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail'),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-calendar-alt',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'rewrite'             => true,
		'has_archive'         => true, //TODO
		'exclude_from_search' => false, //true show on query search
		'publicly_queryable'  => true,
		'query_var' => true,
		'capability_type'     => 'post',
		'register_meta_box_cb' => 'gravitation_portfolios_add_post_type_metabox'
	);

		register_post_type( 'gv_portfolios', $args );
		//flush_rewrite_rules();
 
		register_taxonomy( 'gravitation_portfolios_cat', // register custom taxonomy - category
			'gv_portfolios',
			array(
				'hierarchical' => true,
				'show_in_nav_menus'   => true,
				'labels' => array(
					'name' => 'Portfolios Category',
					'singular_name' => 'Portfolio category',
				),
				'rewrite' => array(
							'slug' => 'gravitation_portfolios_cat', 
							'hierarchical' => true,
							),
			)
		);
		
	}
	add_action( 'init', 'gravitation_portfolios_create_post_type' );
	
	function gravitation_portfolios_add_post_type_metabox() { // add the meta box
		add_meta_box( 'gravitation_portfolios_metabox', 'Additional information about this portfolio', 'gravitation_portfolios_metabox', 'gv_portfolios', 'normal' );
	}
 
	function gravitation_portfolios_metabox() {
		global $post;

		echo '<input type="hidden" name="portfolios_post_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
		
		//$portfolios_post_price = get_post_meta($post->ID, '_portfolios_post_price', true);
		$portfolios_post_client = get_post_meta($post->ID, '_portfolios_post_client', true);
		$portfolios_post_website = get_post_meta($post->ID, '_portfolios_post_website', true);
		
		echo '<table class="form-table">
	
		<tr valign="top">
        <th>
        
	        <label></label>
        <?php
        </th>
        <td>
         
        </td>
        </tr>	
				<th>';
				?>
				
		<!--label><?php  _e('Precio','gravitation_portfolios'); ?></label-->
		<?php /*
				echo '</th>
				<td>
					<input type="text" name="portfolios_post_price" class="regular-text" value="' . $portfolios_post_price . '"> 
				</td>
			</tr>
			
			
			<tr>
				<th>';
				*/ ?>
		<label><?php _e('Client','gravitation_portfolios'); ?></label>
		<?php
				echo '</th>
				<td>
					<input type="text" name="portfolios_post_client" class="regular-text" value="' . $portfolios_post_client . '"> 
				</td>
			</tr>
			<tr>
				<th>';
				?>
		<label><?php _e('Website','gravitation_portfolios'); ?></label>
		<?php
				echo '</th>
				<td>
					<input type="text" name="portfolios_post_website" class="regular-text" value="' . $portfolios_post_website . '"> 
				</td>
			</tr>';
			
			
		echo '</table>';
	
	}
 
    function gravitation_portfolios_post_save_meta( $post_id, $post ) { // save the data

		 if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		  return;
 
		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */
 
		if ( ! isset( $_POST['portfolios_post_noncename'] ) ) { // Check if our nonce is set.
			return;
		}
 
		if( !wp_verify_nonce( $_POST['portfolios_post_noncename'], plugin_basename(__FILE__) ) ) { // Verify that the nonce is valid.
			return $post->ID;
		}
 
		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times
		if( !wp_verify_nonce( $_POST['portfolios_post_noncename'], plugin_basename(__FILE__) ) ) {
			return $post->ID;
		}
 
		// is the user allowed to edit the post or page?
		if( ! current_user_can( 'edit_post', $post->ID )){
			return $post->ID;
		}
		// ok, we're authenticated: we need to find and save the data
		// we'll put it into an array to make it easier to loop though
		//$portfolios_post_meta['_portfolios_post_price'] = $_POST['portfolios_post_price'];
		$portfolios_post_meta['_portfolios_post_client'] = $_POST['portfolios_post_client'];
		$portfolios_post_meta['_portfolios_post_website'] = $_POST['portfolios_post_website'];
 
		// add values as custom fields
		foreach( $portfolios_post_meta as $key => $value ) { // cycle through the $portfolios_post_meta array

			$value = implode(',', (array)$value); // if $value is an array, make it a CSV (unlikely)
			if( get_post_meta( $post->ID, $key, FALSE ) ) { // if the custom field already has a value
				update_post_meta($post->ID, $key, $value);
			} else { // if the custom field doesn't have a value
				add_post_meta( $post->ID, $key, $value );
			}
			if( !$value ) { // delete if blank
				delete_post_meta( $post->ID, $key );
			}
		}
	}
	add_action( 'save_post', 'gravitation_portfolios_post_save_meta', 1, 2 ); // save the custom fields

endif; // end of function_exists()

?>
<?php
require_once("meta-box-class/my-meta-box-class.php");
if (is_admin()){
  /* 
   * prefix of meta keys, optional
   * use underscore (_) at the beginning to make keys hidden, for example $prefix = '_ba_';
   *  you also can make prefix empty to disable it
   * 
   */
  $prefix = 'ba_';
  
  /* 
   * configure your meta box
   */
  $config2 = array(
    'id'             => 'demo_meta_box2',          // meta box id, unique per meta box
    'title'          => 'Advanced Meta Box fields',          // meta box title
    'pages'          => array('gv_portfolios'),      // post types, accept custom post types as well, default is array('post'); optional
    'context'        => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
    'priority'       => 'high',            // order of meta box: high (default), low; optional
    'fields'         => array(),            // list of meta fields (can be added by field arrays)
    'local_images'   => false,          // Use local or hosted images (meta box images for add/remove)
    'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
  );
  /*
   * Initiate your 2nd meta box
   */
  $my_meta2 =  new AT_Meta_Box($config2);
 
  $repeater_fields[] = $my_meta2->addImage($prefix.'image_field_id',array('name'=> 'Image'),true);
  /*
   * Then just add the fields to the repeater block
   */
  //repeater block
  $my_meta2->addRepeaterBlock($prefix.'re_',array(
    'inline'   => true, 
    'name'     => 'This is a Repeater Block',
    'fields'   => $repeater_fields, 
    'sortable' => true
  ));
 
  //Finish Meta Box Declaration 
  $my_meta2->Finish();
}
?>