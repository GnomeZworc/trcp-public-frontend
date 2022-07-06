<?php
include './includes/sessions.php';
include './includes/template.php';

Template::view('templates/donate.html', [
    'page' => basename($_SERVER['PHP_SELF'])
]);
?>
