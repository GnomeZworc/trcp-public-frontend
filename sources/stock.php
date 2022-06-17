<?php
include './includes/template.php';
include './api/v1/stock.php';

Template::view('templates/stock.html', [
    'page' => basename($_SERVER['PHP_SELF']),
    'list_product' => $list_product,
    'list_categories' => $list_categories,
    'panier_resum' => $panier_resum,
    'categorie_id' => $categorie_id
]);
?>
