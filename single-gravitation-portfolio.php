<?php

/** 
 * The Template for displaying all single posts.
 * 
 * @package Gravitation portfolios
 * @author Ulises Freitas
 */
get_header(); 

global $wp_query;
$post_id = $wp_query->get_queried_object_id();

$class = 'left';
if( $class == 'left' ){
  
    $right_class = 'col-xs-12 col-sm-9 col-md-9 pull-right'; 
    $left_class = 'col-xs-12 col-sm-3 col-md-3 pull-left'; 
    $class = 'left';
}
elseif( $class == 'right' ){
    
    $right_class = 'col-xs-12 col-sm-9 col-md-9';     
    $left_class = 'col-xs-12 col-sm-3 col-md-3';   
    $class = 'right';
}
else{
    $class = '';
}
?>
<div class="container">
    <section class="project-section"> 
        <section class="row"> 
        <?php     
            if( $class ) echo '<article>';  
            
		        			while ( have_posts() ) {
		        			   the_post();
		                       include( plugin_dir_path(__FILE__).'content-single-gravitation-portfolio.php' );          
		            		}	   
                    		
            if( $class ) echo '</article>';   
        ?>
        
        </section> <!--row end-->
    </section> <!--project-section end-->
</div><!--container end-->
<?php get_footer(); ?>