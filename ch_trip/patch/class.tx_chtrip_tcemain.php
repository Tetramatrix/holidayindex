<?php

class tx_chtrip_tcemain {
	
	/**
	 * Delete all related accommodation and categories of a location
	 *
	 * @params	string	tce command
	 * @params	string	table name
	 * @params	int		uid
	 * @params	int		???
	 * @params	pointer	parent object
	 * @return	void
	 */	
	 
	function processCmdmap_preProcess($command, $table, $id, $value, &$pObj) {		
		
		if ($command == 'delete' && $table == 'tx_chtrip_location') {		
			# Deleteing accommodations from this vacation
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_chtrip_accommodation','parent_uid='.$id);					
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$pObj->deleteRecord('tx_chtrip_accommodation',$row['uid'], 0);	
				
				# Deleteing categories from this vacation
				$resCat = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_chtrip_category','parent_uid='.$row['uid']);					
				while ($rowCat = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resCat)) {
					$pObj->deleteRecord('tx_chtrip_category',$rowCat['uid'], 0);
				}				
			}			
		} else if ($command == 'delete' && $table == 'tx_chtrip_accommodation') {		
			# Deleteing categories from this accommodation
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*','tx_chtrip_category','parent_uid='.$id);				
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
				$pObj->deleteRecord('tx_chtrip_category',$row['uid'], 0);
			}			
		}		
		
	}
}

?>