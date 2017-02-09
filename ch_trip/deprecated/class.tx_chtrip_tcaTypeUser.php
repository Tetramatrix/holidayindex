<?php
class tx_chtrip_tcaTypeUser {
	
	/**
	 * Gets the parent object uid from the parent object array or from the _GET() url params and return a hidden field with this uid 
	 *
	 * @params	array	parent object
	 * @return	string	html
	 */	

	function hiddenUID($PA, $fobj) {		
		global $tx_chtrip_parent_uid;
		
		if (empty($PA['row']['parent_uid'])) {
			# create form 			
			$getParams = explode('&',t3lib_div::_GET('returnUrl'));
			foreach ($getParams as $key) {
				$temp = explode("=",$key);
				$getParams[$temp[0]]=$temp[1];
			}
			if ($getParams['P[uid]']) {
				$tx_chtrip_parent_uid = $getParams['P[uid]'];				
				$hiddenfield = '<input type="hidden" name="'.$PA['itemFormElName'].'" value="'.$getParams['P[uid]'].'">';
			}			
		} else {		
			# edit form
			if (preg_match('/|/',$PA['row']['parent_uid'])) {
				$parent_uid = explode('|',$PA['row']['parent_uid']);
				$hiddenfield = '<input type="hidden" name="'.$PA['itemFormElName'].'" value="'.$parent_uid[0].'">';			
			} else {
				$hiddenfield = '<input type="hidden" name="'.$PA['itemFormElName'].'" value="'.$PA['row']['parent_uid'].'">';
			}
		}
		return $hiddenfield;
	}
}