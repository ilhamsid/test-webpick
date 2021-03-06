<?php
require(dirname(__FILE__) . '/wp-load.php');
require_once( ABSPATH . '/wp-admin/includes/taxonomy.php');
require_once( ABSPATH . '/wp-admin/includes/post.php');

// lecture fichier csv
function read($csv){
    $file = fopen($csv, 'r');
    while (!feof($file) ) {
        $line[] = fgetcsv($file, 1024);
    }
    fclose($file);
    return $line;
}
// fichier des marques/modèles
$csv = 'csv_marque_Modele_290318.csv';


/*** création catégories ***/
$cat_name = "Marques auto";
$cat_id = create_category("Catégorie", $cat_name);

$csv = read($csv);
for ($i=1; $i < count($csv)-1; $i++) { 
	$line = $csv[$i][0];
	$explode = explode(';', $line);

	$marque_name = iconv('UTF-8', 'ISO-8859-1//IGNORE', $explode[0]);
	$modele_name = iconv('UTF-8', 'ISO-8859-1//IGNORE', $explode[1]);

	$marque_id = create_category("Marque", $marque_name, $cat_id);
	$modele_id = create_category("Modèle", $modele_name, $marque_id);
}

function create_category($type, $name, $parent=null){
	$id = category_exists($name);
	if($id)
		echo 'La catégorie "'.$type.' : '.$name.'" existe déjà !<br>';
	else{
		wp_insert_category(
	        array(
	            'cat_name'        => $name,
	            'category_parent' => $parent,
	        )
	    );	
		echo 'Catégorie "'.$type.' : '.$name.'" ajoutée avec succes !<br>';	
		$id = category_exists($name);
	}
	return $id;
}
echo '<br><br>';
// script articles
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
   	if($parent_name != "" && $parent_name != $cat_name){  

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