<?php
include './includes/sessions.php';
include './includes/template.php';

Template::view('templates/index.html', [
    'page' => basename($_SERVER['PHP_SELF'])
]);
?>
