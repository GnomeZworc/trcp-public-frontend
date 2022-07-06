<?php
include './includes/sessions.php';
include './includes/template.php';
include './includes/databases.php';

$dbconn = pg_connect("host=".$db_host." port=".$db_port." dbname=".$db_database." user=".$db_user." password=".$db_password)
    or die('Connexion impossible : ' . pg_last_error());

$uuid = '';

if (isset($_POST['email']) && isset($_POST['custom']) && isset($_POST['project'])
    && $_POST['email'] != ''){
    $uuid = guidv4();
    $custom = pg_escape_string($_POST['custom']);
    $project = pg_escape_string($_POST['project']);
    $query = 'INSERT INTO basket(uuid, email, custom, project) VALUES(\''.$uuid.'\', \''.$_POST['email'].'\', \''.$custom.'\', \''.$project.'\')';
    pg_query($query) or die('Échec de la requête : ' . pg_last_error());
    $query = 'SELECT id FROM basket WHERE uuid = \''.$uuid.'\'';
    $result = pg_query($query) or die('Échec de la requête : ' . pg_last_error());
    $results = pg_fetch_assoc($result);
    foreach ($_SESSION['panier'] as $elem){
        $query = 'UPDATE basket_element SET basket_id = '.$results['id'].' WHERE uuid = \''.$elem['uuid'].'\'';
        pg_query($query) or die('Échec de la requête : ' . pg_last_error());
    }
    session_destroy();
} else if (isset($_POST['uuid'])) {
    $uuid = $_POST['uuid'];
} else if (isset($_GET['uuid'])){
    $uuid = $_GET['uuid'];
} else {
    $url = "https://teamrecup.fr/stock.php";
    header("Location: ".$url);
    die();
}

$query = 'SELECT * FROM basket WHERE uuid = \''.$uuid.'\'';
$result = pg_query($query) or die('Échec de la requête : ' . pg_last_error());
$results = pg_fetch_assoc($result);

if (!array_key_exists( 'id', $results )){
    $url = "https://teamrecup.fr/stock.php";
    header("Location: ".$url);
    die();
}

$basket_id = $results['id'];
$basket_email = $results['email'];
$basket_custom = $results['custom'];
$basket_project = $results['project'];
$basket_status = $results['status'];
$basket_cost = $results['cost'];
$basket_raison = $results['raison'];

$query = 'SELECT product_id, cost, count(*) FROM basket_element WHERE basket_id = '.$basket_id.' GROUP BY product_id, cost';
$result = pg_query($query) or die('Échec de la requête : ' . pg_last_error());
$total_cost = 0;
$list_basket = array();
$elem = array();
while ($elems = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    $query_product = 'SELECT * FROM product WHERE id = '.$elems['product_id'];
    $result_product = pg_query($query_product) or die('Échec de la requête : ' . pg_last_error());
    $results = pg_fetch_assoc($result_product);
    $elem['title'] = $results['titre'];
    $elem['desc'] = $results['description'];
    $elem['quantity'] = $elems['count'];
    $elem['unit_cost'] = $elems['cost'];
    $elem['total_cost'] = $elems['cost'] * $elems['count'];
    $total_cost += $elem['total_cost'];
    array_push($list_basket, $elem);
}

pg_close($dbconn);

if ($basket_cost == 0){
    $basket_cost = $total_cost;
}

Template::view('templates/submit.html', [
    'page' => basename($_SERVER['PHP_SELF']),
    'uuid' => $uuid,
    'custom' => $basket_custom,
    'project' => $basket_project,
    'status' => $basket_status,
    'list' => $list_basket,
    'cost' => $basket_cost,
    'why' => $basket_raison
]);
?>
