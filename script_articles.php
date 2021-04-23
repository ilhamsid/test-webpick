<?php
require(dirname(__FILE__) . '/wp-load.php');
require_once( ABSPATH . '/wp-admin/includes/taxonomy.php');
require_once( ABSPATH . '/wp-admin/includes/post.php');

$cat_mere = "Marques auto";
$args = array(
    'hide_empty'          => 0,
    'order'               => 'ASC',
    'orderby'             => 'name',
    'taxonomy'            => 'category',
);
// récupérer toutes les catégories
$categories = get_categories($args);
foreach($categories as $category) {
	$parent = $category->category_parent;
   	$parent_name = get_cat_name($parent);

   	// si la catégorie est un modèle
   	if($parent_name != "" && $parent_name != $cat_mere){   	
	   	echo '- '.$category->term_id.' : '.$category->name.'<br>';
	   	echo '==> '.$parent.' : '.$parent_name.'<br>';

	   	$title = wp_strip_all_tags($parent_name." - ".$category->name);
	   	$content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";

	   	$id = post_exists($title, $content);
	   	if($id)
			echo 'L\'article "'.$title.'" existe déjà !<br>';
		else{
			// création article pour le modèle
			$post = array(
			  	'post_title'    => $title,
			  	'post_content'  => $content,
			  	'post_status'   => 'publish',
			  	'post_author'   => 1,
			);

			$post_id = wp_insert_post($post);
			// attribuer la catégorie à l'article
			$cats = array($parent, $category->term_id);
			wp_set_object_terms($post_id, $cats, 'category');
			echo 'Article "'.$title.'" créé avec succes !<br>';			
		}
   	}
}
