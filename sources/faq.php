<?php
include './includes/sessions.php';
include './includes/template.php';

Template::view('templates/faq.html', [
    'page' => basename($_SERVER['PHP_SELF'])
]);
?>
