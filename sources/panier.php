<?php
include 'sessions.php';
include 'template.php';
include 'databases.php';

$dbconn = pg_connect("host=".$db_host." port=".$db_port." dbname=".$db_database." user=".$db_user." password=".$db_password)
    or die('Connexion impossible : ' . pg_last_error());

/* Remove for backet */
if (isset($_GET['remove'])){
    $index_to_remove = -1;
    foreach ($_SESSION['panier'] as $id => $elem){
        if ($_GET['id'] == $elem['id']){
            $index_to_remove = $id;
            break;
        }
    }
    if ($index_to_remove != -1){
        $uuid = $_SESSION['panier'][$index_to_remove]['uuid'];
        $query = 'DELETE FROM basket_element WHERE uuid = \''.$uuid.'\'';
        pg_query($query) or die('Échec de la requête : ' . pg_last_error());
        unset ($_SESSION['panier'][$index_to_remove]);
    }
}

/* count elem in backet */
$panier_resum = array();
foreach ($_SESSION['panier'] as $elem){
    if (isset($panier_resum[$elem['id']])){
        $panier_resum[$elem['id']] += 1;
    }
    else {
        $panier_resum[$elem['id']] = 1;
    }
}

$list = array();
$already_done = array();
$total_cost = 0;
foreach ($_SESSION['panier'] as $elem){
    if (!in_array($elem['id'], $already_done, true)){
        $query = 'SELECT * FROM product WHERE id = '.$elem['id'];
        $result = pg_query($query) or die('Échec de la requête : ' . pg_last_error());
        while ($elems = pg_fetch_array($result, null, PGSQL_ASSOC)){
            $elem['id'] = $elems['id'];
            $elem['title'] = $elems['titre'];
            $elem['desc'] = $elems['description'];
            $elem['quantity'] = $panier_resum[$elem['id']];
            $elem['type'] = $elems['categorie'];
            $elem['unit_cost'] = $elems['cost'];
            $elem['total_cost'] = $elems['cost'] * $elem['quantity'];
            $total_cost += $elem['total_cost'];
            array_push($list, $elem);
        }
        pg_free_result($result);
        array_push($already_done, $elem['id']);
    }
}

pg_close($dbconn);

Template::view('templates/panier.html', [
    'page' => basename($_SERVER['PHP_SELF']),
    'list' => $list,
    'total_cost' => $total_cost
]);
?>