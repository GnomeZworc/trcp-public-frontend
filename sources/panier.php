<?php
include './includes/template.php';
include './api/v1/panier.php';

Template::view('templates/panier.html', [
    'page' => basename($_SERVER['PHP_SELF']),
    'list' => $list,
    'total_cost' => $total_cost
]);
?>
