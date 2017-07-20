<?php /* Template Name: Gravitation Portfolios   */ ?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
	    <div class="container">
			<div class="col-md-12"> 
		            <div class="text-center">
			        	<ul id="filterOptions" class="filterOptions visible-lg visible-md visible-sm">
							<li class="active"><a href="#" class="all"><?php _e('All','gravitation_portfolios');?></a></li>
					<?php
					
						$args = array(
						    'orderby'                => 'name',
						    'order'                  => 'ASC',
						    'hide_empty'             => true,
						    'include'                => array(),
						    'exclude'                => array(),
						    'exclude_tree'           => array(),
						    'number'                 => '',
						    'offset'                 => '',
						    'fields'                 => 'all',
						    'name'                   => '',
						    'slug'                   => '',
						    'hierarchical'           => true,
						    'search'                 => '',
						    'name__like'             => '',
						    'description__like'      => '',
						    'pad_counts'             => false,
						    'get'                    => '',
						    'child_of'               => 0,
						    'parent'                 => '',
						    'childless'              => false,
						    'cache_domain'           => 'core',
						    'update_term_meta_cache' => true,
						    'meta_query'             => ''
						);
						$terms = get_terms('gravitation_portfolios_cat', $args );
						$count = count($terms); 
						$i=0;
						$term_list = '';
						if ($count > 0) {
							foreach ($terms as $term) {
								$i++;
								if($term->count !=  0){
									$term_list .= '<li><a href="#" class="items-'. $term->slug .'">' . $term->name . ' <span></span></a></li>';
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
				
			<div class="col-md-12">
				<ul class="ourHolder list-unstyled list-inline grid">
				<?php 
					
					$portfolio_count = get_option( 'portfolios_count' );

					$args = array( 
						'post_type' => 'gv_portfolios',
						'posts_per_page' => ( !empty( $portfolio_count ) ) ? $portfolio_count : '3' ,
						'paged' => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1,
					);
					$query = new WP_Query($args);
				
					// Begin The Loop
					if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); 
			
					// Get The Taxonomy 'Filter' Categories
					$terms = get_the_terms( get_the_ID(), 'gravitation_portfolios_cat' ); 
					
					$large_image =  wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full', false, '' ); 
					$large_image = $large_image[0]; 
					
					
					?>
					
					<li class="item col-md-4" data-id="id-<?php echo get_the_ID(); ?>" data-type="items-<?php foreach ($terms as $term) { echo $term->slug; } ?>">
					
					<a href="<?php echo get_permalink( get_the_ID() );; ?>">
						<?php if ( has_post_thumbnail() ):
							the_post_thumbnail( 'gravitation-portfolios-thumbnail' );?>
						<?php else: ?>
							<img src="<?php echo plugins_url().'/gravitation-portfolio/images/set_a_thumbnail.png';?>" alt="">
						<?php endif; ?>
						<?php // echo __('View More','gravitation-portfolios'); ?>
					</a>
									
					
					</li>
					
				
					<?php endwhile; ?>
					<?php endif; ?>
					<?php wp_reset_query(); ?>
			
				</ul>
				<div class="clearfix"></div>
				</div>
				<!-- pagination here -->
				    <?php
				      if (function_exists('custom_pagination')) {
				        custom_pagination($query->max_num_pages,"",$paged);
				      }
				    ?>
		</div>
    </main>
</div>

<?php get_footer(); ?>