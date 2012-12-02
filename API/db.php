<?php

try {
  $db = new PDO("pgsql:host=localhost;dbname=nowaiste", "waiste_user2", "waiste_pass");
}

catch(PDOException $e) {
  $db = null;
  echo 'ERREUR DB: ' . $e->getMessage();
}

?>
