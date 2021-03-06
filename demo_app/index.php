<?php

/**
 * @copyright Copyright 2013 Markus Kalkbrenner, bio.logis GmbH (https://www.biologis.com)
 * @license GPLv2
 * @author Markus Kalkbrenner <info@bio.logis.de>
 */

use biologis\HV\HVClient;
use biologis\HV\HVRawConnectorUserNotAuthenticatedException;
use biologis\HV\HVRawConnectorAuthenticationExpiredException;

require '../vendor/autoload.php';

$appId = file_get_contents('app.id');

session_start();

ob_start();

print "Connecting HealthVault ...<br><hr>";
ob_flush();

$hv = new HVClient(
  $appId,
  $_SESSION
);

try {
  ob_start();

  $hv->connect(file_get_contents('app.fp'), 'app.pem');

  $personInfo = $hv->getPersonInfo();

  $personId = $personInfo->person_id;
  $recordId = $personInfo->selected_record_id;

  print 'person-id: <b>' . $personId . '</b><br>';
  print 'name: <b>' . $personInfo->name . '</b><br>';
  print 'preferred-culture language: <b>' . $personInfo->preferred_culture->language . '</b><br>';
  print '<hr>';

  ob_flush();

  print '<br><a href="list_all_things.php">List all things</a>';
  print '<br><a href="files.php">Files example</a>';
  print '<br><a href="weight.php">Weight example</a>';
  print '<br><a href="immunization.php">Immunization example</a>';
}
catch (HVRawConnectorUserNotAuthenticatedException $e) {
  print "You're not authenticated! ";
  printAuthenticationLink();
}
catch (HVRawConnectorAuthenticationExpiredException $e) {
  print "Your authentication expired! ";
  printAuthenticationLink();
}
catch (Exception $e) {
  print "Your authentication expired! ";
  printAuthenticationLink();
}


function printAuthenticationLink() {
  global $hv;

  print '<a href="' . $hv->getAuthenticationURL(
    'http' . (!empty($_SERVER["HTTP_SSL"]) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])
    . '">Authenticate</a>';
}
