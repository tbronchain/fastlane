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
  var_dump($request);

  $qr_code = $request['qr_code'];
  $last_name = $request['last_name'];
  $first_name = $request['first_name'];
  $phone_number = $request['phone_number'];
  $email = $request['email'];

  addUser($qr_code, $last_name, $first_name, $phone_number, $email);

  // response
  // none
}

function getPosition($request) {
  $phone_number = $request['phone_number'];

  $status = getFieldOfUser("status", $phone_number);
  $position = getFieldOfUser("position", $phone_number);
  $time = getTime($position);

  // response
  $response =
    array(
          'place' => $position,
          'estimated_time' => $time,
          'status' => $status
          );
  echo json_encode($response);
}

function validateClient() {
  $phone_number = $request['phone_number'];

  setFieldOfUser("status", "0", $phone_number);
  decreaseAllUsers($phone_number);

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

  setFieldOfUser("rate", $rate, $phone_number);
  $emailAddress = getFieldOfUser("email", $phone_number);
  $subject = 'mail subject';
  $content = 'mail content';

  sendMail($emailAddress, $subject, $content);

  // reponse
  // none
}

function getList() {
  $list = getGlobalList();

  // response
  $response = $list;
  echo json_encode($response);
}

$mode = $_REQUEST['mode'];

switch ($mode) {
case "add_queue":
  // POST
  // Action: Add client in queue / Get nothing
  // Req: Client -> Server
  // Res: None
  addQueue(json_decode($_REQUEST['data'], true));
  break;
case "get_position":
  // POST
  // Action: Ask position / Get position + time + status in queue
  // Req: Client -> Server
  // Res: Server -> Client
  getPosition(json_decode($_REQUEST['data'], true));
  break;
case "validate_client":
  // POST
  // Action: Validate client / Get nothing
  // Req: Cashier -> Server
  // Res: None
  validateClient(json_decode($_REQUEST['data'], true));
  break;
case "rate":
  // POST
  // Action: Rate queue / Get nothing
  // Req: Client -> Server
  // Res: None
  rate(json_decode($_REQUEST['data'], true));
  break;
case "get_list":
  // GET
  // Action: Ask queue / Get queue
  // Req: Cashier -> Server
  // Res: Server -> Cashier
  getList(json_decode($_REQUEST['data'], true));
  break;
}

/*
BACKEND:
Send SMS (Twilio)
*/
?>
