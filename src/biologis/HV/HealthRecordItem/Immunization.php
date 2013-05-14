<?php

/**
 * @copyright Copyright 2013 Markus Kalkbrenner, bio.logis GmbH (https://www.biologis.com)
 * @license GPLv2
 * @author Itthichok Jangjaimon
 */

namespace biologis\HV\HealthRecordItem;

use biologis\HV\HealthRecordItemData;
use biologis\HV\HealthRecordItemFactory;

/**
 * Class Immunization.
 * @see http://msdn.microsoft.com/en-us/library/microsoft.health.itemtypes.immunization.aspx
 */
class Immunization extends HealthRecordItemData {

  /**
   * @see http://msdn.microsoft.com/en-us/library/microsoft.health.itemtypes.immunization.aspx
   *
   * @param $name_text
   * @param $expirationDate_y
   * @param $expirationDate_m
   * @param $expirationDate_d
   * @param $administrationDate_structured_timestamp
   * @return object File
   * 
   * Note: We may make classes from all basic types
   * see http://developer.healthvault.com/pages/types/type.aspx?id=3e730686-781f-4616-aa0d-817bba8eb141
   */
  public static function createFromData($name_text, $expirationDate_y, 
    $expirationDate_m, $expirationDate_d,
    $administrationDate_structured_timestamp

    /* Possible arguments generated automatically.
    $name_text, $name_code_value, $name_code_family, 
    $name_code_type, 
    $name_code_version,
    ${'administration-date_structured_timestamp'},
    $administrator_name_full, $administrator_name_title_text, 
    $administrator_name_title_code_value, $administrator_name_title_code_family,
    $administrator_name_title_code_type, $administrator_name_title_code_version,
    $administrator_name_first, $administrator_name_middle, 
    $administrator_name_last,
    $administrator_organization,
    ${'administrator_professional-training'},
    $administrator_contact_address_description,
    ${'administrator_contact_address_is-primary'},
    $administrator_contact_address_street,
    $administrator_contact_address_city,
    $administrator_contact_address_state,
    $administrator_contact_address_postcode,
    $administrator_contact_address_country,
    $administrator_contact_phone_description,
    ${'administrator_contact_phone_is-primary'},
    $administrator_contact_phone_number,
    $administrator_contact_email_description,
    ${'administrator_contact_email_is-primary'},
    $administrator_contact_email_address,
    $manufacturer_text,
    $manufacturer_code_value,
    $manufacturer_code_family,
    $manufacturer_code_type,
    $manufacturer_code_version,
    $lot
    $route_text, $route_code_value, $route_code_family, 
    $route_code_type, $route_code_version
    ${'expiration-date_y'}, ${'expiration-date_m'}, ${'expiration-date_d'},
    ${'anatomic-surface_text'},
    ${'anatomic-surface_code_value'},
    ${'anatomic-surface_code_family'},
    ${'anatomic-surface_code_type'},
    ${'anatomic-surface_code_version'} */) {

    $immunization = HealthRecordItemFactory::getThing('Immunization #(v2)');

	$immunization->getQp()->top('immunization > name text')->text($name_text);
    $immunization->setTimestamp('immunization > administration-date structured', $administrationDate_structured_timestamp);
	$immunization->getQp()->top('immunization > expiration-date y')->text($expirationDate_y);
	$immunization->getQp()->top('immunization > expiration-date m')->text($expirationDate_m);
	$immunization->getQp()->top('immunization > expiration-date d')->text($expirationDate_d);

    #default values, causes invalid argument exception if not set.
    $immunization->getQp()->top('immunization > administrator contact address is-primary')->text('true');
    $immunization->getQp()->top('immunization > administrator contact phone is-primary')->text('true');
    $immunization->getQp()->top('immunization > administrator contact email is-primary')->text('true');

    #print $immunization->getQp()->top()->xml();
    /*
    $immunization->setTimestamp('immunization > administration-date structured timestamp', ${'administration-date_structured_timestamp'});
    $immunization->getQp()->top()->find('immunization > administrator name full')->text($administrator_name_full);
    $immunization->getQp()->top()->find('immunization > administrator name title text')->text($administrator_name_title_text);
    $immunization->getQp()->top()->find('immunization > administrator name title code value')->text($administrator_name_title_code_value);
    $immunization->getQp()->top()->find('immunization > administrator name title code family')->text($administrator_name_title_code_family);
    $immunization->getQp()->top()->find('immunization > administrator name title code type')->text($administrator_name_title_code_type);
    $immunization->getQp()->top()->find('immunization > administrator name title code version')->text($administrator_name_title_code_version);
    $immunization->getQp()->top()->find('immunization > administrator name first')->text($administrator_name_first);
    $immunization->getQp()->top()->find('immunization > administrator name middle')->text($administrator_name_middle);
    $immunization->getQp()->top()->find('immunization > administrator name last')->text($administrator_name_last);
    $immunization->getQp()->top()->find('immunization > administrator organization')->text($administrator_organization);
    $immunization->getQp()->top()->find('immunization > administrator professional-training')->text(${'administrator_professional-training'});
    $immunization->getQp()->top()->find('immunization > administrator contact address description')->text($administrator_contact_address_description);
    $immunization->getQp()->top()->find('immunization > administrator contact address is-primary')->text(${'administrator_contact_address_is-primary'});
    $immunization->getQp()->top()->find('immunization > administrator contact address street')->text($administrator_contact_address_street);
    $immunization->getQp()->top()->find('immunization > administrator contact address ')->text($administrator_contact_address_city);
    $immunization->getQp()->top()->find('immunization > administrator contact address ')->text($administrator_contact_address_state);
    $immunization->getQp()->top()->find('immunization > administrator contact address ')->text($administrator_contact_address_postcode);
    $immunization->getQp()->top()->find('immunization > administrator contact address ')->text($administrator_contact_address_country);
    */

    return $immunization;
  }
}
