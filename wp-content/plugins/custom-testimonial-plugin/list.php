<?php

# Generate Shortcode For custom_testimonials

function homepage_shortcode_ss($atts){

    $atts = shortcode_atts(
        array(
            'id'  => false,
        ),
        $atts,
        'timeline_shortcode_ss'
    );
    ob_start();
    $query = new WP_Query( array(
        'post_type' => 'custom_testimonials',
        'posts_per_page' => 15,
        'order' => 'DESC',
        'orderby' => 'date',
                'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field'    => 'id',
                'terms'    => $atts['id'],
            ),
        ),

    ) );
    
    $count = $query->post_count;
    
    if ( $query->have_posts() ) { ?>
       <div class="testimonials-main slick_homepage">
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <div class="testmonials-content">
                    <?php echo get_the_post_thumbnail(); ?>
                    <p><?php echo get_the_content(); ?></p>
                    <h3><?php echo get_the_title(); ?></h3>
                    <div class="small"><?php echo get_the_excerpt(); ?></div>
                </div>     
            
            <?php endwhile;
            wp_reset_postdata(); ?>
    </div>
    <?php
    $data = ob_get_clean();
    return $data;
    }
}
 
add_shortcode('smartx-homepage_ss','homepage_shortcode_ss');