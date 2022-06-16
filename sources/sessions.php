<?php
include 'databases.php';

$dbconn = pg_connect("host=".$db_host." port=".$db_port." dbname=".$db_database." user=".$db_user." password=".$db_password)
    or die('Connexion impossible : ' . pg_last_error());

if ( ! session_id() ) {
    session_start();
    if ( ! isset($_SESSION['panier']) ) {
        $_SESSION['panier'] = array();
    }
}

/* auto remove from database */
$query = 'SELECT * FROM basket_element';
$result = pg_query($query) or die('Échec de la requête : ' . pg_last_error());
$date = (new DateTime())->getTimestamp() - 3600;
while ($elems = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    $elemtimestamp = (new DateTime($elems['created_at']))->getTimestamp();
    $diff = $elemtimestamp - $date;
    $hdiff = gmdate("H:i:s", $diff);
    if ($diff < 0 && $elems['basket_id'] == null) {
        $query = 'DELETE FROM basket_element WHERE id = '.$elems['id'];
        pg_query($query) or die('Échec de la requête : ' . pg_last_error());
    }
}

/* auto remove from session */
foreach ($_SESSION['panier'] as $id => $elem){
    $query = 'SELECT count(*) AS rows FROM basket_element WHERE uuid = \''.$elem['uuid'].'\'';
    $res = pg_query($query) or die('Échec de la requête : ' . pg_last_error());
    $line = pg_fetch_assoc($res);
    $nb = $line['rows'];
    if ($nb == 0){
        unset ($_SESSION['panier'][$id]);
    }
}

function guidv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

?>