<?php

require_once(PATH_t3lib.'class.t3lib_tceforms.php');

class tx_chtrip_tcaFormFieldTCEForms extends t3lib_TCEforms {

	/**
	 * Overwrite Typo3 getSingleField_SW()
	 *
	 * @params	string	table name
	 * @params	string	field type
	 * @params	array	parent object "row"
	 * @params	array	parent object
	 * @return	string	html
	 */	
	 
	function getSingleField_SW($table,$field,$row,$PA) {
		switch($PA['fieldConf']['config']['type']) {                                                                                                       
			case 'input':
				$item = $this->getSingleField_typeInput($table,$field,$row,$PA);                                       
			break;
			case 'text':
				$item = $this->getSingleField_typeText($table,$field,$row,$PA);
			break;
			case 'check':
				$item = $this->getSingleField_typeCheck($table,$field,$row,$PA);                                                                                                
			break;                         
			case 'radio':
				$item = $this->getSingleField_typeRadio($table,$field,$row,$PA); 
			break;
			case 'select':
				$item = $this->getSingleField_typeSelect($table,$field,$row,$PA);                                                                                       
			break;
			case 'group':
				$item = $this->getSingleField_typeGroup($table,$field,$row,$PA);                                                                                      
			break;
			case 'none':
				$item = $this->getSingleField_typeNone($table,$field,$row,$PA);
			break;
			case 'user':
				$item = $this->getSingleField_typeUser($table,$field,$row,$PA);
			break;
			case 'flex':
				$item = $this->getSingleField_typeFlex($table,$field,$row,$PA);
			break;
			default:
				$item = $this->getSingleField_typeUnknown($table,$field,$row,$PA);
			break;
		}
		
		return $item;                                                                                        
	}                     
} 
 
class tx_chtrip_tcaFormField {	


	/**
	 * Takes a form field from the tca and render its userFunc
	 *
	 * @params	array parent object
	 * @return	string	html
	 */	
	function formfield($PA) {		
		$form = t3lib_div::makeInstance('tx_chtrip_tcaFormFieldTCEForms');			
		$formField = $form->getSingleField_SW($PA['fieldConf']['config']['userFuncTable'],$PA['fieldConf']['config']['userFuncField'],$PA['row'],$PA);		          
		$GLOBALS['TSFE']->page['tx_chtrip_extjscode'] .= $form->extJSCODE;			
		return $formField;		
	}

	function singleFormField($PA, $fobj) {		
		return '<td>'.$this->formField(&$PA).'</td>';  
	}

	function singleFormFieldPrice ($PA, $fobj) {	
		return '<td>'.$this->formField($PA).'</td>'; 
	}	

	function singleFormFieldFirst($PA, $fobj) {	
		return '
				<table border="0" width="100%" cellspacing="0" cellpadding="4" style="border:solid 1px black;">
					<tr style="background-color:#cbc7c3">
						<td width="20"><img src="clear.gif" width="17" height="18"></td>
						<td width="20"><strong>von</strong></td>
						<td witdh="20"><strong>bis</strong></td>
						<td width="40"><strong>Grundpreis</strong></td>
						<td width="99%"><strong>Halbpension</strong></td>
					</tr>
					<tr style="background-color:#e4e0db">
						<td><div style="margin-left:10px;margin-right:10px;"><strong>'.$PA['fieldConf']['config']['userFuncTitle'].'</strong></div></td>					
						<td>'.$this->formField($PA).'</td>';  
	}
	

	function singleFormFieldStartRowTitle($PA, $fobj) {
		return '<tr style="background-color:#e4e0db">
							<td colspan="5" style="	border-top-width: 0px;
											border-right-width: 0px;
											border-bottom-width: 1px;
											border-left-width: 0px;
											border-bottom-style: solid;
											border-top-color: #000000;
											border-right-color: #000000;
											border-bottom-color: #00000;
											border-left-color: #000000;">
							</td>
				</tr>	
				<tr style="background-color:#e4e0db;">
					<td><div style="margin-left:10px;margin-right:10px;"><strong>'.$PA['fieldConf']['config']['userFuncTitle'].'</strong></div></td>
					<td>'.$this->formField($PA).'</td>';  
	}
	
	function singleFormFieldStartRowTitleAlt($PA, $fobj) {
		return '<tr style="background-color:#e4e0db">
							<td colspan="5" style="	border-top-width: 0px;
											border-right-width: 0px;
											border-bottom-width: 1px;
											border-left-width: 0px;
											border-bottom-style: solid;
											border-top-color: #000000;
											border-right-color: #000000;
											border-bottom-color: #00000;
											border-left-color: #000000;">
							</td>
				</tr>		
				<tr style="background-color:#e4e0db;border-top:solid 1px black;">
					<td><div style="margin-left:10px;margin-right:10px;"><strong>'.$PA['fieldConf']['config']['userFuncTitle'].'</strong></div></td><td>'.$this->formField($PA).'</td>';  
	}

	function singleFormFieldStartRow($PA, $fobj) {
		return '
				<tr style="background-color:#e4e0db">
					<td>&nbsp;</td>
					<td>'.$this->formField($PA).'</td>';
	}

	function singleFormFieldStartRowAlt($PA, $fobj) {
		return '
				<tr style="background-color:#e4e0db">
					<td>&nbsp;</td>
					<td>'.$this->formField($PA).'</td>';  
	}

	function singleFormFieldEndRow ($PA, $fobj) {	
		return '<td>'.$this->formField($PA).'</td></tr>';  
	}

	function singleFormFieldLast ($PA, $fobj) {	
		return '<td>'.$this->formField($PA).'</td></tr></table>'; 
	}	
	
	function singleFormField2EndRow ($PA, $fobj) {
		return '<td>'.$this->formField($PA).'</td><td></td><td></td></tr>'; 	
	}
	
	function singleFormFieldLastRow ($PA, $fobj) {
		$form = t3lib_div::makeInstance('tx_chtrip_tcaFormFieldTCEForms');	
		return '<td>'.$this->formField($PA).'</td><td></td><td></td></tr>'.$form->printNeededJSFunctions().'<script type="text/javascript">'.$GLOBALS['TSFE']->page['tx_chtrip_extjscode'].'</script>';	
	}
}
?>