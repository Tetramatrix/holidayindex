<?php

require_once(PATH_t3lib.'class.t3lib_xml.php');

class tx_chtrip_t3lib_xml extends t3lib_xml {
	
	var $finds;	

	/**
	 * Initialize "anonymous" XML document with <?xml + <!DOCTYPE header tags and setting ->topLevelName as the first level.
	 * Encoding is set to UTF-8!
	 *
	 * @return	void
	 */
	function renderHeader()	{
		$this->lines[]='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
		$this->newLevel($this->topLevelName,1);
	}

	/**
	 * Wraps the value in tags with element name, $field.
	 *
	 * @param	string		Fieldname from a record - will be the element name
	 * @param	string		Value from the field - will be wrapped in the elements.
	 * @return	string		The wrapped string.
	 */
	function fieldWrapSearchString($field,$value)	{
		return '<searchstring id="'.$field.'">'.$value.'</searchstring>';
	}

	/**
	 * Wraps the value in tags with element name, $field.
	 *
	 * @param	string		Fieldname from a record - will be the element name
	 * @param	string		Value from the field - will be wrapped in the elements.
	 * @return	string		The wrapped string.
	 */
	function fieldWrapPageId($field,$value)	{
		return '<pageid id="'.$field.'">'.$value.'</pageid>';
	}
	
	/**
	 * Takes a tree and traverses it, merging sublevels
	 *
	 * @params	array	tree
	 * @return	void
	 */
	function getTree($tree) {		
		$crazyRecursionLimiter = 999;		
		while ($crazyRecursionLimiter>0 && list($key,$val) = each($tree)) {	
			$crazyRecursionLimiter--;
			switch ($val['_SUB_LEVEL']) {
				case true:				
					switch ($val['invertedDepth']) {
						case 2:
							$this->topLevel=$val['row']['title'];
						break;
						case 1:
							$this->midLevel=$val['row']['title'];
							$this->midLevelUid=$val['row']['uid'];
						break;
					}					
					$nextCount = $this->getTree($val['_SUB_LEVEL']);
				break;
				default:
					if (!$val['row']) {
						$this->finds[$this->midLevel][]=$val;
						$this->finds[$this->midLevel]['uid']=$this->midLevelUid;
					}
				break;
			}			
		}
	}

	/**
	 * Takes a array and add a xml record to the xml document
	 *
	 * @params	string	table name 
	 * @params	array	finds 
	 * @return	void
	 */
	function renderRecords($table,$finds) {
		foreach ($finds as $key => $value) {		
			$params = array (	'title' => $key,
								'uid' => $value['uid'],
								'finds' => sizeOf($finds[$key])-1							
							);		
			$this->addRecord($table,$params);
		}		
	}
}

?>