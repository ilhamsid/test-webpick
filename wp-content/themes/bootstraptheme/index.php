<?php get_header(); ?>



<div class="container">
    <?php
    $cat_mere = get_cat_ID('Marques auto');

    /* $offset = ($posts_per_page * $paged) - $posts_per_page ;*/

    $posts_per_page = 5;
    $page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    $offset = ( $page - 1 );

    $args1 = array(
        'parent' => $cat_mere,
        'hide_empty'     => 0,
        'order'          => 'ASC',
        'orderby'        => 'name',
        'taxonomy'       => 'category',
        'paged' => $paged,
        'posts_per_page' => 5,
    );
    // récupérer toutes les catégories
    $categories = get_categories($args1);
    $nbr_pages = round(count($categories) / $posts_per_page);

    // afficher les catégories 5 par page
    for( $i = $offset * $posts_per_page; $i < ( $offset + 1 ) * $posts_per_page; $i++ ) {
        $category = $categories[$i];

        if(!is_null($category)){
            $cat_id = $category->term_id;
            $args = array(
                    'post_type' => 'post' ,
                    'orderby' => 'date' ,
                    'order' => 'DESC' ,
                    'posts_per_page' => 4,
                    'cat'         => $cat_id
                ); 
            $req = new WP_Query($args);
            ?>            
            <div class="row mb-5">
                <h2 class="text-primary mt-5"><?php echo $category->name; ?></h2>
                <?php 
                if ($req->have_posts()) { 
                    while ($req->have_posts()) : $req->the_post();
                ?>
                    <div class="col-md-3">
                        <h4><?php the_title(); ?></h4>
                        <p class="text-justify"><?php the_content(); ?></p>
                    </div>
                <?php
                    endwhile;
                }else
                    echo 'Aucun article'; 
                ?>
            </div>
            <?php
        }
    }
    unset($category);
    ?>  

    <nav>
        <ul class="pager">
            <?php if($paged > 1){ ?>
                <li><?php previous_posts_link('Précédent'); ?></li>
            <?php } ?>
            <?php if($paged < $nbr_pages){ ?>
                <li><?php next_posts_link('Suivant'); ?></li>
            <?php } ?>
        </ul>
    </nav>
</div>
<?php get_footer(); ?>