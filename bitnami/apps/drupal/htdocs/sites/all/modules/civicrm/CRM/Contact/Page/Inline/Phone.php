<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.2                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2012                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
 */

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2012
 * $Id$
 *
 */

/**
 * Dummy page for details of Phone
 *
 */
class CRM_Contact_Page_Inline_Phone extends CRM_Core_Page {

  /**
   * Run the page.
   *
   * This method is called after the page is created.
   *
   * @return void
   * @access public
   *
   */
  function run() {
    // get the emails for this contact
    $contactId = CRM_Utils_Request::retrieve('cid', 'Positive', CRM_Core_DAO::$_nullObject, TRUE, NULL, $_REQUEST);

    $locationTypes = CRM_Core_PseudoConstant::locationDisplayName();
    $phoneTypes = CRM_Core_PseudoConstant::phoneType();

    $entityBlock = array('contact_id' => $contactId);
    $phones = CRM_Core_BAO_Phone::getValues($entityBlock);
    if (!empty($phones)) {
      foreach ($phones as $key => & $value) {
        $value['location_type'] = $locationTypes[$value['location_type_id']];
        $value['phone_type'] = $phoneTypes[$value['phone_type_id']];
      }
    }

    $contact = new CRM_Contact_BAO_Contact( );
    $contact->id = $contactId;
    $contact->find(true);
    $privacy = array( );
    foreach ( CRM_Contact_BAO_Contact::$_commPrefs as $name ) {
      if ( isset( $contact->$name ) ) {
        $privacy[$name] = $contact->$name;
      }
    }

    $this->assign('contactId', $contactId);
    $this->assign('phone', $phones);
    $this->assign('privacy', $privacy);

    // check logged in user permission
    CRM_Contact_Page_View::checkUserPermission($this, $contactId);
    
    // finally call parent 
    parent::run();
  }
}

