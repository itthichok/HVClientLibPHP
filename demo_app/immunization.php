<?php

/**
 * @copyright Copyright 2013 Markus Kalkbrenner, bio.logis GmbH (https://www.biologis.com)
 * @license GPLv2
 * @author Itthichok Jangjaimon
 */

use biologis\HV\HVClient;
use biologis\HV\HealthRecordItem\Immunization;
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

  if (isset($_POST['submit']) && isset($_POST['name_text']) 
      && isset($_POST['expirationDate_y']) && isset($_POST['expirationDate_m']) 
      && isset($_POST['expirationDate_d'])) {

    $immunization = Immunization::createFromData(
                      $_POST['name_text'], $_POST['expirationDate_y'],
                      $_POST['expirationDate_m'], $_POST['expirationDate_d'], time());
    $hv->putThings($immunization, $recordId);
  }

  $things = $hv->getThings('Immunization #(v2)', $recordId);
  foreach ($things as $thing) {
    print date(DATE_RFC850, $thing->getTimestamp('immunization > administration-date structured timestamp')) . ':';
    print ' <b>Name</b> ' . $thing->immunization->name->text;
    print ' <b>Exp (y:</b> ' . $thing->immunization->{'expiration-date'}->y;
    print ' <b>m:</b> ' . $thing->immunization->{'expiration-date'}->m;
    print ' <b>d:</b> ' . $thing->immunization->{'expiration-date'}->d 
        . '<b>)</b>';
    print ' <br>';
  }
  print "<hr>";
  ob_flush();
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
  print $e->getMessage() . '<br>';
  print $e->getCode() . '<br>';
  printAuthenticationLink();
}


function printAuthenticationLink() {
  global $hv;

  print '<a href="' . $hv->getAuthenticationURL(
    'http' . (!empty($_SERVER["HTTP_SSL"]) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])
    . '">Authenticate</a>';
}

?>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  Name: <input name="name_text" type="text" value="Anthrax vaccine">
  Expiration  (y: <input name="expirationDate_y" type="text" value="2013">
  m: <input name="expirationDate_m" type="text" value="12">
  d: <input name="expirationDate_d" type="text" value="31">)
  <input type="submit" name="submit">
</form>
