<?php

try {
  $db = new PDO("pgsql:host=localhost;dbname=waiste", "theonegri", "");
}
catch(PDOException $e) {
  $db = null;
  echo 'ERREUR DB: ' . $e->getMessage();
}
 


?>
