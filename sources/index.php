<?php
include 'sessions.php';
include 'template.php';

Template::view('templates/index.html', [
    'page' => basename($_SERVER['PHP_SELF'])
]);
?>