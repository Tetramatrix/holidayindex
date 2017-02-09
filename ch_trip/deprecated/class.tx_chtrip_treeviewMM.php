<?php


class user_chtrip_treeviewMM {

	function main(&$config,&$params,&$p0bj) {

		$selItems = explode(',',$config['row']['hotelproperties']);
		
		$result = array();
		foreach($selItems as $key => $value) {
			$uid = explode('|',$value);
			$result[]=$uid[0];			
		}

		$config['items']=$result;
	}
}