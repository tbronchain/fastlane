<?php

try {
  $db = new PDO("pgsql:host=localhost;dbname=waiste", "waiste_user", "waiste_pass");
}

catch(PDOException $e) {
  $db = null;
  echo 'ERREUR DB: ' . $e->getMessage();
}

?>
