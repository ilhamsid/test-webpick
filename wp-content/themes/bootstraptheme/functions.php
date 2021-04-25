<?php

function bootstrapstarter_enqueue_styles() {
    wp_register_style('bootstrap', get_template_directory_uri() . '/bootstrap/css/bootstrap.min.css' );
    $dependencies = array('bootstrap');
	wp_enqueue_style( 'bootstrapstarter-style', get_stylesheet_uri(), $dependencies ); 
}

function bootstrapstarter_enqueue_scripts() {
    $dependencies = array('jquery');
    wp_enqueue_script('bootstrap', get_template_directory_uri().'/bootstrap/js/bootstrap.min.js', $dependencies, '', true );
}

add_action( 'wp_enqueue_scripts', 'bootstrapstarter_enqueue_styles' );
add_action( 'wp_enqueue_scripts', 'bootstrapstarter_enqueue_scripts' );

function bootstrapstarter_wp_setup() {
    add_theme_support( 'title-tag' );
}

add_action( 'after_setup_theme', 'bootstrapstarter_wp_setup' );



function getLastBrand($args) {
	$brand = $args['brand'];
	$cat_id = get_cat_ID($brand);
	echo '<h3 class="text-info mt-5">Derniers articles '.$brand.'</h3>';

	$args = array(
            'post_type' => 'post' ,
            'orderby' => 'date' ,
            'order' => 'DESC' ,
            'posts_per_page' => 4,
            'cat'         => $cat_id
        ); 
    $req = new WP_Query($args);


    if ($req->have_posts()) { 
        while ($req->have_posts()) : $req->the_post();
    ?>
        <div class="col-md-12">
            <h4><?php the_title(); ?></h4>
            <p class="text-justify"><?php the_content(); ?></p>
        </div>
    <?php
        endwhile;
    }else
        echo 'Aucun article'; 

}
add_shortcode( 'last_brand', 'getLastBrand' );
?>