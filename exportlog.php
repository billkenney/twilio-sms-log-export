<?php
// Get the PHP helper library from twilio.com/docs/php/install
require __DIR__ . '/vendor/autoload.php'; // Loads the library. This may vary depending on how you installed the library.
use Twilio\Rest\Client;
// Your Account Sid and Auth Token from twilio.com/user/account
$sid = $_POST['sid'];
$token = $_POST['authToken'];
$start = $_POST['from'];
$end = $_POST['to'];
$phone = $_POST['phone'];
$date = '';

$client = new Client($sid, $token);

if (!empty($start) || !empty($end)){

  if ($start == $end){
    $date = array
    (
      'dateSent' => $start
    );

    if (!empty($phone)){
      $date = array
      (
        'dateSent' => $start,
        'From' => $phone
      );
      $date2 = array
      (
        'dateSent' => $start,
        'To' => $phone
      );
    }
  }

  if (!empty($start) && empty($end)){
    $date = array
    (
      'dateSentAfter' => $start
    );

    if (!empty($phone)){
      $date = array
      (
        'dateSentAfter' => $start,
        'From' => $phone
      );
      $date2 = array
      (
        'dateSentAfter' => $start,
        'To' => $phone
      );
    }
  }

  if (empty($start) && !empty($end)){
    $date = array
    (
      'dateSentBefore' => $end
    );

    if (!empty($phone)){
      $date = array
      (
        'dateSentBefore' => $end,
        'From' => $phone
      );
      $date2 = array
      (
        'dateSentBefore' => $end,
        'To' => $phone
      );
    }
  }

  if (!empty($start) && !empty($end) && $start != $end){
    $date = array
    (
      'dateSentAfter' => $start,
      'dateSentBefore' => $end
    );

    if (!empty($phone)){
      $date = array
      (
        'dateSentAfter' => $start,
        'dateSentBefore' => $end,
        'From' => $phone
      );
      $date2 = array
      (
        'dateSentAfter' => $start,
        'dateSentBefore' => $end,
        'To' => $phone
      );
    }
  }
}

if (empty($start) && empty($end) && !empty($phone)){
  $date = array
  (
    'From' => $phone
  );
  $date2 = array
  (
    'To' => $phone
  );
}

$messages = $client->messages->stream($date);

// /* Browser magic */
$filename = $sid."_sms.csv";
header("Content-Type: application/csv");
header("Content-Disposition: attachment; filename={$filename}");

/* Write headers */
$fields = array( 'SMS Message SID', 'Account SID', 'From', 'To', 'Body', 'Date Sent', 'Date Created', 'Date Updated', 'Direction', 'Status', 'API Version', 'Num Media', 'Message Segments', 'Error Code', 'Error Message' );
echo '"'.implode('","', $fields).'"'."\n";

/* Write rows */
foreach ($messages as $sms) {
  $row = array(
    $sms->sid,
    $sms->accountSid,
    $sms->from,
    $sms->to,
    $sms->body,
    $sms->dateSent->format('Y-m-d H:i:s'),
    $sms->dateCreated->format('Y-m-d H:i:s'),
    $sms->dateUpdated->format('Y-m-d H:i:s'),
    $sms->direction,
    $sms->status,
    $sms->apiVersion,
    $sms->numMedia,
    $sms->numSegments,
    $sms->errorCode,
    $sms->errorMessage
  );

  echo '"'.implode('","', $row).'"'."\n";
}

if (!empty($phone)){
  $messages = $client->messages->stream($date2);

  /* Write rows */
  foreach ($messages as $sms) {
    $row = array(
      $sms->sid,
      $sms->accountSid,
      $sms->from,
      $sms->to,
      $sms->body,
      $sms->dateSent->format('Y-m-d H:i:s'),
      $sms->dateCreated->format('Y-m-d H:i:s'),
      $sms->dateUpdated->format('Y-m-d H:i:s'),
      $sms->direction,
      $sms->status,
      $sms->apiVersion,
      $sms->numMedia,
      $sms->numSegments,
      $sms->errorCode,
      $sms->errorMessage
    );

    echo '"'.implode('","', $row).'"'."\n";
  }
}
