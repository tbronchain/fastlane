<?php
include 'db.php';

/*function do_query ($query) {
	global $db;
	if ($db) {
		$qry = $db->prepare($query);
		$qry->execute();

	}
}
/*CREATE TABLE queue (
    qrcode       varchar(200) NOT NULL,
    phone         integer NOT NULL,
    email   varchar(200),
    firstname        varchar(200) NOT NULL,
    lastname         varchar(200) NOT NULL,
    rate         integer,
    time         integer NOT NULL,
    status     boolean,
    review    varchar(500),
    picture    varchar(500)
);*/
/*do_query("INSERT INTO queue VALUES ('qrcode', '0672332439', 'theo.negri@epitech.eu', 'theo', 'negri', '0','12345890', '1', 'test', 'toto.jpg')");*/
?>