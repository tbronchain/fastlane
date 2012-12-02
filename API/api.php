<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Authorization");
error_reporting(0);

require_once 'db.php';

function do_query ($type,$query) {
	global $db;
	if ($db) {
		if ($type == "select"){
			$qry = $db->query($query);
			$noms = $qry->fetchAll(PDO::FETCH_ASSOC);
			return $noms;
		}
		if ($type == 'insert') {
			echo $query;
			$db->query($query) or die(print_r($db->errorInfo(), true));
                        //$db->errorInfo();
			//var_dump($qry);
		}
		if ($type == 'update') {
			echo $query;
			$db->query($query) or die(print_r($db->errorInfo(), true));
			//var_dump($qry);
		}

	}
}

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

  do_query("insert", "INSERT INTO queue VALUES ('".$phone_number."', '".$qr_code."', '".$email."', '".$first_name."', '".$last_name."', '0','".time()."', 'true', 'na', 'na');");

  $array = do_query("select", "select * from queue WHERE status=true;");
  $key = array_search($phone_number, $array);
  $key++;
  $time = getTime($key);
  $text = "Hello ".$first_name.". You are in position ".$key.". You have ".$time."min to wait.";

  // SMS
  require_once('Twilio.php');
  // Your Account SID from www.twilio.com/user/account
  $sid = "AC414d5a114ab94591b59933f2efebdd3a";
  // Your Auth Token from www.twilio.com/user/account
  $token = "cfa5251dd317d9a28a74f1d11430b575";

  $client = new Services_Twilio($sid, $token);
  $message =
    $client->account->sms_messages->create('+16507310469', // From a valid Twilio number
                                           '+33672332439', // Text this number
                                           $text);

  // mail
  require_once('php-mailjet.class-mailjet-0.1.php');
  $mj = new Mailjet();
  $params = array('method' => 'POST',
                  'contact' => $email,
                  'id' => '127955');
  $response = $mj->listsAddContact($params);
  // response
  // none
}

function getPosition($request) {

  $phone_number = $request['phone_number'];
  //$phone_number = substr($phone_number, 1);

  $res = do_query("select", "select * from queue where phone_number='".$phone_number."';");
  $review = $res[0]['review'];
$status = $res[0]['status'];
  $array = do_query("select", "select * from queue;");
$i = 0;
while($array[$i]['phone_number'] != null) {
	if ($array[$i]['phone_number'] == $phone_number) {
		break;
	}
	$i++;
}

$i++;
  // response
  $response =
    array('place' => $i,
          'estimated_time' => getTime($i),
          'status' => $status,
			'review' => $review);
  echo json_encode($response);
}

function validateClient($request) {

  $phone_number = $request['phone_number'];

  do_query("update", "UPDATE queue SET status='false' WHERE phone_number='".$phone_number."';");
  // reponse
  // none
}

function rate($request) {
  $phone_number = $request['phone_number'];
  // string
  //$qr_code = $request['qr_code'];
  // int 0 -> 5
  $rate = $request['rate'];
  // base64
  $picture = $request['picture'];
  $review = $request['review'];

  do_query("update", "UPDATE queue SET review='".$review."', rate='".$rate."', picture='".$picture."' WHERE phone_number='".$phone_number."';");

  // reponse
  // none
}

function getList() {
  $response = do_query("select", "select * from queue WHERE status=true;");
  //file_put_contents("/tmp/response.query", $response);
  //file_put_contents("/tmp/response.json", json_encode($response));
  echo json_encode($response);
}

function getList2() {
  $response = do_query("select", "select * from queue;");
  //file_put_contents("/tmp/response.query", $response);
  //file_put_contents("/tmp/response.json", json_encode($response));
  echo "<pre>";var_dump($response);echo "</pre>";
}

function del() {
  $response = do_query("insert", "delete from queue;");
  //file_put_contents("/tmp/response.query", $response);
  //file_put_contents("/tmp/response.json", json_encode($response));
  echo "<pre>";var_dump($response);echo "</pre>";
}

//$dump = print_r($_REQUEST, true);
//file_put_contents("./test.txt", $_REQUEST);

$mode = $_REQUEST['mode'];
$data = str_replace("\\", "", $_REQUEST['data']);

$data = json_decode($data, true);
switch ($mode) {
case "add_queue":
  addQueue($data);
  break;
case "get_position":
  getPosition($data);
  break;
case "validate_client":
  validateClient($data);
  break;
case "rate":
  rate($data);
  break;
case "get_list":
  getList($data);
  break;
case "get_list2":
  getList2($data);
  break;
case "qwertyuiop":
  del($data);
  break;
}

?>
