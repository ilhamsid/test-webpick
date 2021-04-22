<?php
require(dirname(__FILE__) . '/wp-load.php');

$cat_name = "Marques auto";
$parent = null;
$id = category_exists($cat_name);
if ( $id ) {
	echo 'La catégorie "'.$cat_name.'" existe déjà !';
	return $id;
}else{
	wp_insert_term(
		$cat_name,
		'category',
		array(
		  'description'	=> '',
		  'slug' 		=> 'marques-auto'
		)
	);	
	echo 'Catégorie "'.$cat_name.'" ajoutée avec succes !';
}

function category_exists( $cat_name, $parent = null ) {
    $id = term_exists( $cat_name, 'category', $parent );
    if ( is_array( $id ) ) {
        $id = $id['term_id'];
    }
    return $id;
}