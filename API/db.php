<?php

try {
  $db = new PDO("pgsql:host=ec2-54-243-188-54.compute-1.amazonaws.com;dbname=daa2s29n9bc1rc;port=5482", "niccqepsqcfqje", "pFj187fLqhhxZHBmDf0dOEiHhH");
}

catch(PDOException $e) {
  $db = null;
  echo 'ERREUR DB: ' . $e->getMessage();
}

?>
