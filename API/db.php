<?php

require_once 'configDB.php';

$connect = new PDO('mysql:host='.$PARAMDB_host.';port='.$PARAMDB_port.';dbname='.$PARAMDB_DB, $PARAMDB_user, $PARAMDB_pass);

?>
