<?php
/*The template for displaying content in the single-gravitation-portfolio.php template
 *
 * @package Gravitation portfolios
 * @author Ulises Freitas
 */

$portfolios_price = get_post_meta(get_the_ID(), '_portfolios_post_price', true);
$portfolios_client = get_post_meta(get_the_ID(), '_portfolios_post_client', true); 
$portfolios_website = get_post_meta(get_the_ID(), '_portfolios_post_website', true); 

$terms = get_the_terms(get_the_ID(), 'gravitation_portfolios_cat');

$count = 0;
if(!empty($term->name)){
	foreach ( $terms as $term ){ 
		$count++ ; 
	    $skill_name[$count] = $term->name; 
	}  
}
$all_images = get_post_meta(get_the_ID(), 'ba_re_', true);
$meta_images_url = '';
foreach ($all_images as $field ) {
if (!in_array($field, array('ba_image_field_id')))
	$meta_images_url[] = $field['ba_image_field_id']['url'];
}
?>

<article class="col-md-10 col-md-offset-1 project-slider fadeIn animated well how-it-works-block">
    <div id="myCarousel" class="carousel slide" data-interval="6000" data-ride="carousel">
        <!-- Carousel indicators -->
        <?php 
        echo '<ol class="carousel-indicators">';
            for( $i=0; $i < count($meta_images_url); $i++ ){
                    $slider_class = '';
                    if( $i == 0 )
                        $slider_class = 'active';
                     
                    echo '<li data-target="#myCarousel" data-slide-to="' . $i . '" class="' . $slider_class . '"></li>';
            } 
        echo '</ol>';
         ?>
        <!-- Carousel items -->
        <div class="carousel-inner">
            <?php 
           
                for( $i=0; $i < count($meta_images_url); $i++ )
                {
                    $slider_img_class = '';
                    
                    if( $i == 0 )
                        $slider_img_class = 'active';

                     echo '<div class="item ' . $slider_img_class . '">'; 
	                    if( !empty( $meta_images_url[$i]) )
	                    {
							echo '<img src="' . $meta_images_url[$i] . '" alt="' . get_the_title() . '" />';
						}
                   		else{ 
							echo '<img src="http://placehold.it/800x400" alt="" />';
						}
                    echo '</div>';
                }
            ?>
        </div>

        <!-- Carousel nav -->
        <a class="carousel-control left" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a class="carousel-control right" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
     
    </div>
    
    <br />
    
    <div class="col-md-8">
    	<div class="headline">
       		<h2><?php echo get_the_title(); ?></h2>
    	</div>	
	   	<p><?php echo the_content(); ?></p>
	</div>

     <?php
	$bookings_post_page_id = get_post_meta(get_the_ID(), '_portfolios_post_page', true);
	$bookings_post_page = esc_url( get_permalink($bookings_post_page_id) );
	?>
    <div class="col-md-4">    
		<div class="headline">
       		<h2><?php echo __('Details','gravitation_portfolios'); ?></h2>
    	</div>
		<ul class="list-unstyled portfolio-details">
			
			<li><strong><?php echo __( 'Client: ', 'gravitation_portfolios' ); ?></strong> <?php echo $portfolios_client; ?></li>
			<li><strong><?php echo __( 'Date: ', 'gravitation_portfolios' ); ?></strong> <?php echo get_the_date(); ?></li>
			<li><strong><?php echo __( 'Categories: ', 'gravitation_portfolios' ); ?></strong> <?php foreach ($terms as $term) { echo $term->name; } ?></li>
			
		</ul>
		<br />
		<a href="<?php echo esc_url($portfolios_website); ?>" class="btn btn-primary btn-lg btn-block"><?php echo __( 'View this work ', 'gravitation_portfolios' ); ?></a>
     </div>
    
    
</article>