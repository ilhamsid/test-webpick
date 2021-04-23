<?php
require(dirname(__FILE__) . '/wp-load.php');
require_once( ABSPATH . '/wp-admin/includes/taxonomy.php');

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