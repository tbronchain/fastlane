<?php
 
// Include Mailjet's API Class
include_once('php-mailjet.class-mailjet-0.1.php');
 
// Create a new Object
$mj = new Mailjet();

$params = array(
    'method' => 'POST',
    'contact' => 'mail3@example.com',
    'id' => '127955'
);
# Call
$response = $mj->listsAddContact($params);
 
# Result
$contact_id = $response->contact_id;
 
?>