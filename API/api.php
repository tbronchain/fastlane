<?php

function getTime($position) {
  $unitTime = 5;
  return $position * $unitTime;
}

// return field of user
function getFieldOfUser($key, $phone_number) {
}

// return list of users in queue
function getGlobalList() {
}

// return nothing
function setFieldOfUser($key, $value, $phone_number) {
}

// return nothing
function addUser($qr_code, $last_name, $first_name, $phone_number, $email) {
}

// return nothing
function decreaseAllUsers($phone_number) {
}

// return nothing
function sendMail($emailAddress, $subject, $content) {
  mail($emailAddress, $subject, $content);
}

function addQueue($request) {
  $qr_code = $request['qr_code'];
  $last_name = $request['last_name'];
  $first_name = $request['first_name'];
  $phone_number = $request['phone_number'];
  $email = $request['email'];

  do_query("insert", "INSERT INTO queue VALUES ('.$qr_code.', '.$phone_number.', '.$email.', '.$first_name.', '.$last_name.', '0','.time().', 'true', 'na', 'na');");
	$array = do_query("select", "select * from queue;");
	$key = array_search($phone_number, $array);
	$key++;
	$time = $key * 5;
	$text = "Hello ".$first_name.". You are in position ".$key.". You have ".$time."min to wait.";
	require('Twilio.php');

	$sid = "AC414d5a114ab94591b59933f2efebdd3a"; // Your Account SID from www.twilio.com/user/account
	$token = "cfa5251dd317d9a28a74f1d11430b575"; // Your Auth Token from www.twilio.com/user/account

	$client = new Services_Twilio($sid, $token);
	$message = $client->account->sms_messages->create(
	  '+16507310469', // From a valid Twilio number
	  '+33672332439', // Text this number
	  $text
	);	
  // response
  // none
}

function getPosition($request) {
  $phone_number = $request['phone_number'];

	$array = do_query("select", "select * from queue;");
	$key = array_search($phone_number, $array);
	$key++;
  // response
  $response =
    array(
          'place' => $key,
          'estimated_time' => $key*5,
          );
  echo json_encode($response);
}

function validateClient() {
  $phone_number = $request['phone_number'];

  do_query("update", "UPDATE queue SET status='false' WHERE phone='.$phone_number.';");

  // reponse
  // none
}

function rate($request) {
  $phone_number = $request['phone_number'];
  // string
  $qr_code = $request['qr_code'];
  // int 0 -> 5
  $rate = $request['rate'];
  // base64
  $picture = $request['picture'];
  $review = $request['review'];


  do_query("update", "UPDATE queue SET review='.$review.', rate='.$rate.', picture='.$picture.' WHERE phone='.$phone_number.';");

  // reponse
  // none
}

function getList() {
  $response = do_query("select", "select * from queue;");
  echo json_encode($response);
}

$mode = $_REQUEST['mode'];

switch ($mode) {
case "add_queue":
  addQueue($_REQUEST);
  break;
case "get_position":
  getPosition($_REQUEST);
  break;
case "validate_client":
  validateClient($_REQUEST);
  break;
case "rate":
  rate($_REQUEST);
  break;
case "get_list":
  getList($_REQUEST);
  break;
}

/*
BACKEND:
Send SMS (Twilio)
*/
?>
