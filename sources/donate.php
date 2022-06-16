<?php
include 'sessions.php';
include 'template.php';

Template::view('templates/donate.html', [
    'page' => basename($_SERVER['PHP_SELF'])
]);
?>