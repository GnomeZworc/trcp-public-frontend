<?php
include 'sessions.php';
include 'template.php';

Template::view('templates/faq.html', [
    'page' => basename($_SERVER['PHP_SELF'])
]);
?>