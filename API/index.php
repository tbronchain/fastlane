<?php
require_once 'db.php';

function do_query ($type,$query) {
	global $db;
	if ($db) {
		if ($type == "select"){
			$qry = $db->query($query);
			
			$noms = $qry->fetchAll();
			print_r($noms);
		}
		if ($type == 'insert') {
			echo $query;
			$db->query($query) or die(print_r($db->errorInfo(), true));
			//var_dump($qry);
		}
		if ($type == 'update') {
			echo $query;
			$db->query($query) or die(print_r($db->errorInfo(), true));
			//var_dump($qry);
		}

	}
}

//do_query("insert","INSERT INTO queue VALUES ('qrcode', '0742362339', 'eric.khun@epitech.eu', 'eric', 'khun', '0','98654321', 'true', 'teset', 'teeoto.jpg');");
//do_query("select", "select * from queue;");
//do_query("update", "UPDATE queue SET firstname='jet' WHERE phone='0742362339';");
?>