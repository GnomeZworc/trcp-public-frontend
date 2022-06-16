<?php
include 'sessions.php';
include 'template.php';
include 'databases.php';

/* count elem in backet */
$panier_resum = array();
foreach ($_SESSION['panier'] as $elem){
    if (isset($panier_resum[$elem['type']])){
        $panier_resum[$elem['type']] += 1;
    }
    else {
        $panier_resum[$elem['type']] = 1;
    }
}

$dbconn = pg_connect("host=".$db_host." port=".$db_port." dbname=".$db_database." user=".$db_user." password=".$db_password)
    or die('Connexion impossible : ' . pg_last_error());

/* add server in basket */
$elems = array();
if (isset($_GET['add']) && $panier_resum[$_GET['type']] < 2){
    $element_query = 'SELECT count(*) AS rows FROM basket_element WHERE product_id = '.$_GET['id'];
    $product_query = 'SELECT quantity AS rows, cost FROM product WHERE id = '.$_GET['id'];
    $element_result = pg_query($element_query) or die('Échec de la requête : ' . pg_last_error());
    $product_result = pg_query($product_query) or die('Échec de la requête : ' . pg_last_error());
    $element_count = pg_fetch_assoc($element_result)['rows'];
    $product = pg_fetch_assoc($product_result);
    $product_count = $product['rows'];
    pg_free_result($element_result);
    if ($element_count < $product_count){
        $myuuid = guidv4();
        $query = 'INSERT INTO basket_element(uuid, product_id, cost) VALUES(\''.$myuuid.'\', '.$_GET['id'].', '.$product['cost'].')';
        pg_query($query) or die('Échec de la requête : ' . pg_last_error());
        $elems['id'] = $_GET['id'];
        $elems['type'] = $_GET['type'];
        $elems['uuid'] = $myuuid;
        $elems['cost'] = $product['cost'];
        array_push($_SESSION['panier'], $elems);
    }
    pg_free_result($product_result);
}

/* Generate categorie list */
$query = 'SELECT * FROM categories';
$result = pg_query($query) or die('Échec de la requête : ' . pg_last_error());
$categorie_id = -1;
$list_categories = array();
$elem = array();
while ($elems = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    $elem['id'] = $elems['id'];
    $elem['name'] = $elems['name'];
    $elem['categorie_type'] = $elems['categorie_type'];
    if (!isset($_GET['categorie_id']) && $elems['categorie_type'] == 'server'){
        $elem['highlight'] = True;
        $categorie_id = $elems['id'];
    } elseif (isset($_GET['categorie_id']) && $_GET['categorie_id'] == $elems['id']){
        $elem['highlight'] = True;
        $categorie_id = $elems['id'];
    } else {
        $elem['highlight'] = False;
    }
    array_push($list_categories, $elem);
}
pg_free_result($result);

/* Generate stock list */
$query = 'SELECT product.id,product.titre,product.description,product.quantity,categories.categorie_type,product.cost FROM product LEFT JOIN categories ON product.categorie_id = categories.id WHERE product.categorie_id = '.$categorie_id.' ORDER BY product.titre';
$result = pg_query($query) or die('Échec de la requête : ' . pg_last_error());
$list_product = array();
$elem = array();
while ($elems = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    $elem['id'] = $elems['id'];
    $elem['title'] = $elems['titre'];
    $elem['desc'] = $elems['description'];
    $elem['type'] = $elems['categorie_type'];
    $elem['cost'] = $elems['cost'];
    $query = 'SELECT count(*) AS rows FROM basket_element WHERE product_id = '.$elems['id'];
    $res = pg_query($query) or die('Échec de la requête : ' . pg_last_error());
    $line = pg_fetch_assoc($res);
    $nb = $line['rows'];
    if ($elems['quantity'] - $nb > 0){
        $elem['quantity'] = $elems['quantity'] - $nb;
        array_push($list_product, $elem);
    }
}
pg_free_result($result);

pg_close($dbconn);

$panier_resum = array();
foreach ($_SESSION['panier'] as $elem){
    if (isset($panier_resum[$elem['type']])){
        $panier_resum[$elem['type']] += 1;
    }
    else {
        $panier_resum[$elem['type']] = 1;
    }
}


foreach($list_product as &$elem){
    $quantity = "";
    if ($elem['quantity'] <= 5){
        $quantity = "low";
    } elseif ($elem['quantity'] <= 15) {
        $quantity = "medium";
    } else {
        $quantity = "high";
    }
    $elem['quantity'] = $quantity;
}

Template::view('templates/stock.html', [
    'page' => basename($_SERVER['PHP_SELF']),
    'list_product' => $list_product,
    'list_categories' => $list_categories,
    'panier_resum' => $panier_resum,
    'categorie_id' => $categorie_id
]);
?>