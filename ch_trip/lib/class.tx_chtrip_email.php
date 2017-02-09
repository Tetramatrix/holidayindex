<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2005 Chi Hoang (chibo@gmx.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Plugin 'TRIP-Travel-Information-Presenter' for the 'ch_trip' extension.
 *
 * @author	Chi Hoang <chibo@gmx.de>
 */
 
class tx_chtrip_email  {

	var $missing = array();
	
	var $required = array (	'datefrom' => 'DATEFROMREQ',
							'datetill' => 'DATETILLREQ',
							'nachname' => 'NAMEREQ',
							'vorname' => 'FORENAMEREQ',
							'str' => 'STREETREQ',
							'ort' => 'WHEREREQ',
							'email' => 'EMAILREQ',
						);
						
	var $validate = array ( 'email' => 'EMAILNOTVALID',
							'datefrom' => 'DATEFROMNOTVALID',
							'datetill' => 'DATETILLNOTVALID'
						  );							
	
	var $priority = array (	'High' => 1,
							'Normal' => 3,
							'Low' => 5
							);
							
	/**
	 * Calc unix-time from a date 
	 *
	 * @params 	string	date, valid format is: dd.mm.yyyy  
	 * @return	int		unixtime
	 */	
	
	function unixtime_decode($in_str) {
		$d = explode('.',$in_str);
		$unixtime = mktime('','','',$d[1],$d[0],$d[2]);	
		return $unixtime;	
	}

	/**
	 * Make a HTML-form  
	 *
	 * @params 	pointer parent object
	 * @return	string	html content
	 */								
	function makeForm() { 
			
		$template['total'] = $this->cObj->getSubpart($this->pObj->templateCode,'###REQUEST###');		
		$markerArray['###FORM###'] = $this->pObj->pi_linkTP_keepPIvars_url(array ('mode' => 'send'));		

		# Do required & validate fields
		if (sizeOf($this->missing)>0) {
			foreach ($this->missing as $key => $value) {
				$markerArray['###'.$key.'###'] = $value;
			}		
		} else {
			foreach ($this->required as $key => $value) {
				$markerArray['###'.$value.'###'] = '';
			}
			foreach ($this->validate as $key => $value) {
				$markerArray['###'.$value.'###'] = '';
			}
		}		
   
		$this->doObjArray();
		
		# Get type	
		$objType = $this->getObjType(intval($this->piVars['uid']));
		
		# Get accommodation
		$objAccommodation = $this->getAccommodation();

		# Get location
		$objLocation = $this->getLocation();
        
		# Get region
		$this->getRegion($objLocation['location_f04338f846']);		
		
		# Get category 
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(	'tx_chtrip_category.title',
														'tx_chtrip_category',
														'uid='.intval($this->piVars['cat']));
		$category = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		
		# Do type+location+region+title
		$markerArray['###OBJTYPE###'] = $objType['title'];
		$markerArray['###HOBJTYPE###'] = htmlentities($objType['title']);

		$markerArray['###OBJNAME###'] = $objLocation['title'];
		$markerArray['###HOBJNAME###'] = htmlentities($objLocation['title']);		
		
		$markerArray['###OBJACCOMMODATION###'] = $objAccommodation['title'];
		$markerArray['###HOBJACCOMMODATION###'] = htmlentities($objAccommodation['title']);
        
        $markerArray['###HID_CODE###'] = htmlentities($objLocation['id_code']);
		
		$markerArray['###REGION###'] = implode(', ',$this->regionTitle);
		$markerArray['###HREGION###'] = htmlentities(implode(', ',$this->regionTitle));
		
		$markerArray['###CATEGORY###'] = $category['title'];
		$markerArray['###HCATEGORY###'] = htmlentities($category['title']);

		$markerArray['###DATEFROM###'] = $this->piVars['datefrom'];
		$markerArray['###DATETILL###'] = $this->piVars['datetill'];
		
		$markerArray['###NAME###'] = $this->piVars['nachname'];
		$markerArray['###FORENAME###'] = $this->piVars['vorname'];
		$markerArray['###STREET###'] = $this->piVars['str'];		
		$markerArray['###WHERE###'] = $this->piVars['ort'];		
		$markerArray['###PHONE###'] = $this->piVars['tel'];
		$markerArray['###FAX###'] = $this->piVars['fax'];
		$markerArray['###EMAIL###'] = $this->piVars['email'];
		$markerArray['###NAME2###'] = $this->piVars['name2'];
		$markerArray['###MESSAGE###'] = $this->piVars['nachricht'];
		
		if (sizeOf($this->missing)==0) {
		
			$markerArray['###FRAUCHECKED###'] = 'checked';
			$markerArray['###PRIVATECHECKED###'] = 'checked';
			$markerArray['###NLYES###'] = 'checked';
			
		} else {
		
			if ($this->piVars['anrede'] == 'Frau') {
				$markerArray['###FRAUCHECKED###'] = 'checked';
			} else {
				$markerArray['###FRAUCHECKED###'] = '';
			}
			if ($this->piVars['anrede'] == 'Herr') {
				$markerArray['###HERRCHECKED###'] = 'checked';
			} else {
				$markerArray['###HERRCHECKED###'] = '';
			}				
			if ($this->piVars['rechnung'] == 'PRIVATE') {
				$markerArray['###PRIVATECHECKED###'] = 'checked';
			} else {
				$markerArray['###PRIVATECHECKED###'] = '';
			}
			if ($this->piVars['rechnung'] == 'BUSINESS') {
				$markerArray['###BUSINESSCHECKED###'] = 'checked';
			} else {
				$markerArray['###BUSINESSCHECKED###'] = '';
			}
			if ($this->piVars['nl'] == '1') {
				$markerArray['###NLYES###'] = 'checked';
			} else {
				$markerArray['###NLYES###'] = '';
			}
            if ($this->piVars['nl'] == '0') {
				$markerArray['###NLNO###'] = 'checked';
			} else {
				$markerArray['###NLNO###'] = '';
			}
		}
		
		$content = $this->cObj->substituteMarkerArrayCached($template['total'], $markerArray);		
		return $content;
	}

	/**
	 * Validate the HTML-form 
	 *
	 * 1.) Validate form
	 * 2.) Store values in a table
	 * 3.) Send a plaintext and html mail
	 *
	 * @params 	pointer parent object
	 * @return	string	html content
	 */	

	function sendForm() {
	
		$send = 0;
		
		# Do missing fields
		$this->missing = array();
		
		foreach ($this->required as $key => $value) {
			if ($this->piVars[$key] == '' && !$this->piVars[$key]) {
				$this->missing[$value] = $this->pObj->pi_getLL($value);
			} else {
				$this->missing[$value] = '';
				$send++;
			}
		}
		
		# Validate fields
		
		foreach ($this->validate as $key => $value) {
			switch ($value) {			
				case 'EMAILNOTVALID':
					$valid = preg_match('/^.+\@[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,3})$/',$this->piVars[$key]);
					if(!$valid) {
						$this->missing[$value] = $this->pObj->pi_getLL($value);
					} else {
						$this->missing[$value] = '';
						$send++;
					}
				break;
				case 'DATEFROMNOTVALID':
				case 'DATETILLNOTVALID':
					$valid = preg_match('/[0-9]{2}\.[0-9]{2}\.[0-9]{4}/',$this->piVars[$key]);
					if(!$valid) {
						$this->missing[$value] = $this->pObj->pi_getLL($value);
					} else {
						$this->missing[$value] = '';
						$send++;
					}
				break;
			}		
		}
		
		if ($send == (sizeOf($this->required)+sizeOf($this->validate)) ) {
		
			# Get the send template
			$template['total'] = $this->cObj->getSubpart($this->pObj->templateCode,'###CONFIRMATION###');	

			if ($this->piVars['anrede'] == 'Frau') {
				$salutation = '0';
			} else {
				$salutation = '1';
			}			
			if ($this->piVars['anmeldung'] == 'YES') {
				$enrol = '1';
			}  else {
				$enrol = '0';
			}
			if ($this->piVars['rechnung'] == 'PRIVATE') {
				$billingaddress = '0';
			} else {
				$billingaddress = '1';
			}
            if ($this->piVars['rechnung'] == '1') {
				$nl = '0';
			} else {
				$nl = '1';
			}
            
            # Log request
			$res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_chtrip_request', array (  	'pid' => $this->lConf['sysfolder'],
																									'tstamp' => time(),
																									'crdate' => time(),
																									'region' => $GLOBALS['TYPO3_DB']->quoteStr(html_entity_decode($this->piVars['hregion']),'tx_chtrip_request'),
																									'objtype' => $GLOBALS['TYPO3_DB']->quoteStr(html_entity_decode($this->piVars['objtype']),'tx_chtrip_request'),
																									'objname' => $GLOBALS['TYPO3_DB']->quoteStr(html_entity_decode($this->piVars['objname']),'tx_chtrip_request'),
																									'objaccommodation' => $GLOBALS['TYPO3_DB']->quoteStr(html_entity_decode($this->piVars['objaccommodation']),'tx_chtrip_request'),
																									'category' => $GLOBALS['TYPO3_DB']->quoteStr(html_entity_decode($this->piVars['category']),'tx_chtrip_request'),
																									'idcode' => $GLOBALS['TYPO3_DB']->quoteStr($this->piVars['id_code'],'tx_chtrip_request'),
																									'datefrom' => $this->unixtime_decode($this->piVars['datefrom']),
																									'datetill' => $this->unixtime_decode($this->piVars['datetill']),
																									'name' => $GLOBALS['TYPO3_DB']->quoteStr($this->piVars['nachname'],'tx_chtrip_request'),
																									'forename' => $GLOBALS['TYPO3_DB']->quoteStr($this->piVars['vorname'],'tx_chtrip_request'),
																									'street' => $GLOBALS['TYPO3_DB']->quoteStr($this->piVars['str'],'tx_chtrip_request'),		
																									'where1' => $GLOBALS['TYPO3_DB']->quoteStr($this->piVars['ort'],'tx_chtrip_request'),  																								  'phone' => $GLOBALS['TYPO3_DB']->quoteStr($this->piVars['tel'],'tx_chtrip_request'),
																									'fax' => $GLOBALS['TYPO3_DB']->quoteStr($this->piVars['fax'],'tx_chtrip_request'),
																									'email' => $GLOBALS['TYPO3_DB']->quoteStr($this->piVars['email'],'tx_chtrip_request'),
																									'name2' => $GLOBALS['TYPO3_DB']->quoteStr($this->piVars['name2'],'tx_chtrip_request'),
																									'message' => $GLOBALS['TYPO3_DB']->quoteStr($this->piVars['nachricht'],'tx_chtrip_request'),
																									'salutation' => $salutation,
																									'billingaddress' =>	$billingaddress,
                                                                                                    'nl' => $nl,
																								)
																					);
                                                                                    
            if ($this->piVars['nl'] == '1') {            
            
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(  'count(*)',
                                                                'tt_address',
                                                                'pid='.$this->lConf['PIDnewsletterDisplay'].' AND tt_address.email="'.$GLOBALS['TYPO3_DB']->quoteStr($this->piVars['email'],
                                                                'tx_chtrip_request').'"'.' AND tt_address.hidden=0 and tt_address.deleted=0'
                                                            );
                echo $GLOBALS['TYPO3_DB']->sql_error();
                $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
                
                if ($row['count(*)'] == 0) {                
                    # add to newsletter
                    $res = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tt_address', array (  	                
                                                                                        'pid' => $this->lConf['PIDnewsletterDisplay'],
                                                                                        'tstamp' => time(),
                                                                                        'name' => $GLOBALS['TYPO3_DB']->quoteStr($this->piVars['nachname'],'tx_chtrip_request'),
                                                                                        'tx_chnufarmaddress_vorname' => $GLOBALS['TYPO3_DB']->quoteStr($this->piVars['vorname'],'tx_chtrip_request'),
                                                                                        'address' => $GLOBALS['TYPO3_DB']->quoteStr($this->piVars['str'],'tx_chtrip_request'),                                                                                        'city' => $GLOBALS['TYPO3_DB']->quoteStr($this->piVars['ort'],'tx_chtrip_request'),
                                                                                        'phone' => $GLOBALS['TYPO3_DB']->quoteStr($this->piVars['tel'],'tx_chtrip_request'),
                                                                                        'fax' => $GLOBALS['TYPO3_DB']->quoteStr($this->piVars['fax'],'tx_chtrip_request'),
                                                                                        'email' => $GLOBALS['TYPO3_DB']->quoteStr($this->piVars['email'],'tx_chtrip_request'),
                                                                                        'tx_chnufarmaddress_anrede' => $GLOBALS['TYPO3_DB']->quoteStr($this->piVars['anrede'],'tx_chtrip_request'),
                                                                                        'module_sys_dmail_html' =>	'1'
                                                                                    )
                                                                        );
                }
            }                                                                       
            
			# Get the htmlmail template
			$htmlmailTemplate['total'] = $this->cObj->getSubpart($this->pObj->templateCode,'###HTMLMAIL###');

			$markerArray['###OBJTYPE###'] = html_entity_decode($this->piVars['objtype']);			
			$markerArray['###OBJNAME###'] = html_entity_decode($this->piVars['objname']);			
			$markerArray['###OBJACCOMMODATION###'] = html_entity_decode($this->piVars['objaccommodation']);			
			$markerArray['###REGION###'] = html_entity_decode($this->piVars['hregion']);					
			$markerArray['###CATEGORY###'] = html_entity_decode($this->piVars['category']);
            $markerArray['###ID_CODE###'] = html_entity_decode($this->piVars['id_code']);            
		
			$markerArray['###DATEFROM###'] = $this->piVars['datefrom'];
			$markerArray['###DATETILL###'] = $this->piVars['datetill'];
			
			$markerArray['###SALUTATION###'] = $this->piVars['anrede'];				
			$markerArray['###NAME###'] = $this->piVars['nachname'];
			$markerArray['###FORENAME###'] = $this->piVars['vorname'];
			$markerArray['###STREET###'] = $this->piVars['str'];
			$markerArray['###WHERE###'] = $this->piVars['ort'];
			$markerArray['###PHONE###'] = $this->piVars['tel'];
			$markerArray['###FAX###'] = $this->piVars['fax'];
			$markerArray['###EMAIL###'] = $this->piVars['email'];
			$markerArray['###NAME2###'] = $this->piVars['name2'];
			$markerArray['###MESSAGE###'] = $this->piVars['nachricht'];
			$markerArray['###BILLINGADDRESS###'] = $this->piVars['rechnung'] == 'PRIVATE' ? 'Privataddresse' : 'Gesch�ftsaddresse';
			$markerArray['###NL###'] = $this->piVars['nl'] == '1' ? 'Newsletter' : 'Kein Newsletter';
			$htmlcontent = $this->cObj->substituteMarkerArrayCached($htmlmailTemplate['total'], $markerArray);			

			# Get the plaintext template
			$plaintextTemplate['total'] = $this->cObj->getSubpart($this->pObj->templateCode,'###PLAINTEXTMAIL###');
            
			$markerArray['###OBJTYPE###'] = html_entity_decode($this->piVars['objtype']);			
			$markerArray['###OBJNAME###'] = html_entity_decode($this->piVars['objname']);			
			$markerArray['###OBJACCOMMODATION###'] = html_entity_decode($this->piVars['objaccommodation']);			
			$markerArray['###REGION###'] = html_entity_decode($this->piVars['hregion']);					
			$markerArray['###CATEGORY###'] = html_entity_decode($this->piVars['category']);		
			$markerArray['###ID_CODE###'] = html_entity_decode($this->piVars['id_code']);		
		
			$markerArray['###DATEFROM###'] = $this->piVars['datefrom'];
			$markerArray['###DATETILL###'] = $this->piVars['datetill'];	
	
			$markerArray['###SALUTATION###'] = $this->piVars['anrede'];	
			$markerArray['###NAME###'] = $this->piVars['nachname'];
			$markerArray['###FORENAME###'] = $this->piVars['vorname'];
			$markerArray['###STREET###'] = $this->piVars['str'];
			$markerArray['###WHERE###'] = $this->piVars['ort'];
			$markerArray['###PHONE###'] = $this->piVars['tel'];
			$markerArray['###FAX###'] = $this->piVars['fax'];
			$markerArray['###EMAIL###'] = $this->piVars['email'];
			$markerArray['###NAME2###'] = $this->piVars['name2'];
			$markerArray['###MESSAGE###'] = $this->piVars['nachricht'];
			$markerArray['###BILLINGADDRESS###'] = $this->piVars['rechnung'] == 'PRIVATE' ? 'Privataddresse' : 'Gesch�ftsaddresse';
            $markerArray['###NL###'] = $this->piVars['nl'] == '1' ? 'Newsletter' : 'Kein Newsletter';
			$plaintextcontent = $this->cObj->substituteMarkerArrayCached($plaintextTemplate['total'], $markerArray);

			$recipients = explode(',',$this->lConf['recipients']);
			
			if (sizeOf($recipients) > 1) {				
				for ($i=1;$i<sizeOf($recipients);$i++) {
					$recipient_copy[] = $recipients[$i];
				}
				$recipient_copy = implode(',',$recipient_copy);
				$recipient = array($recipients[0]);
			} else {
				$recipient = array($this->lConf['recipients']);
			}
			
			$htmlmail = t3lib_div::makeInstance('t3lib_htmlmail');
			$htmlmail->start();
			$htmlmail->useBase64();
			$htmlmail->subject = $this->lConf['subject'] != '' ? $this->lConf['subject'].': '.$this->piVars['objname'] : $this->piVars['objname'];
			$htmlmail->from_email = $this->lConf['from_email'];
			$htmlmail->from_name = $this->lConf['from_name'];
			$htmlmail->replyto_email = $this->lConf['replyto_email'];
			$htmlmail->replyto_name = $this->lConf['replyto_name'];
			$htmlmail->organisation = $this->lConf['organisation'];
			$htmlmail->returnPath = $this->lConf['returnPath'];
			
			$htmlmail->priority = $this->priority[$this->lConf['priority']];
			$htmlmail->mailer = 'Typo3 Vacationindex Extension';			
			
			$htmlmail->setHtml($htmlmail->encodeMsg($htmlcontent));
			$htmlmail->addPlain($plaintextcontent);
			
			$htmlmail->setHeaders();			
			$htmlmail->setContent();
			$htmlmail->setRecipient($recipient);			
			$htmlmail->recipient_copy = $recipient_copy;
			
			$result = $htmlmail->sendTheMail();
			
			if (!$result) {
                t3lib_div::debug($this->piVars);
                if (TYPO3_DLOG) {
                    t3lib_div::devLog(
                                        'class tx_chtrip_email: sendmail failed',             
                                        'ch_trip',                                   
                                        3,                                           
                                        array()
                                    );
               }
               die();
			};		
			
			$content = $this->cObj->substituteMarkerArrayCached($template['total'], $markerArray);
		
		} else {
							
			$content = $this->makeForm($this);		
		}		
					
		return $content;	
	}
}

?>