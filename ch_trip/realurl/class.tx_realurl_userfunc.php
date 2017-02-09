<?

class tx_realurl_userfunc  {

    var $sysfolder = 2;

    var $find = array  (    '/ - /',
                            '/\s/',
                            '/\*/',
                            '/ò/',
                            '/è/',
                            '/\(B\&B\)/',
                            '/B\&B/',
                            '/\’/',
                            '/\(App\.\)/',
                            '/,/',
                            '/’/',
                            '/\//',
                            '/"/',
                            '/ü/',
                            '/ä/',
                            '/ö/',
                            '/:/',
                            '/\(/',
                            '/\)/',
                            '/\+/',
                            '/\&/',
                            '/–/',
                            '/-{2,4}/',
                            '/-\./',
                            '/\.{2,4}/',                            
                            '/”/',                            
                            '/“/',                            
                            '/„/',                            
                            '/“/',                       
                            '/\'/',                       
                            '/\.$/',                       
                            '/\-$/',                       
                        );
        
    var $replace = array (  '-',
                            '-',
                            '',
                            'o',
                            'e',
                            'Bed-and-Breakfast',
                            'Bed-and-Breakfast',
                            '-',
                            'App.',
                            '',
                            '',
                            '-',
                            '',
                            'ue',
                            'ae',
                            'oe',
                            '-',
                            '',
                            '',
                            '-',
                            '-',
                            '-',
                            '-',
                            '.',
                            '.',
                            '-',
                            '-',
                            '-',
                            '-',
                            '.',
                            '',
                            '',
                        );
                        
    function location($params, $ref) {

        if ($params['value']=='') return;
        
        if (!is_numeric($params['value'])) {
        
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(	'tx_chtrip_location.uid,tx_chtrip_location.title',
                                                            'tx_chtrip_location',
                                                            'pid='.$this->sysfolder.
                                                            ' AND tx_chtrip_location.deleted=0 AND tx_chtrip_location.hidden=0'
                                                            );
            echo $GLOBALS['TYPO3_DB']->sql_error(); 
            while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                $title = preg_replace($this->find,$this->replace,$row['title']);
                $list[$title]=$row['uid'];            
            }

            $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_chtrip_pi1.']['locUid'] = $list[$params['value']];
            return $list[$params['value']];                 
        
        } else {
        
            $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_chtrip_pi1.']['locUid'] = $params['value'];
            
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(	'tx_chtrip_location.title',
                                                            'tx_chtrip_location',
                                                            'pid='.$this->sysfolder.
                                                            ' AND tx_chtrip_location.deleted=0 AND tx_chtrip_location.hidden=0
                                                              AND tx_chtrip_location.uid='.$params['value']
                                                            );
            echo $GLOBALS['TYPO3_DB']->sql_error();        
            list($title) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
    
            $result = preg_replace($this->find,$this->replace,$title);
        }
        
        return $result;
    }


    function accommodation($params, $ref)    {
    
        if ($params['value']=='') return;       
        
        $value = $params['value'];
        
        if (TYPO3_DLOG)	t3lib_div::devLog("Realurl Segment: Accommodation: value:$value", 'realurl',-1);
        
        if ($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_chtrip_pi1.']['locUid']=='') return;
        
        $uid = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_chtrip_pi1.']['locUid'];
        
        if (TYPO3_DLOG)	t3lib_div::devLog("Realurl Segment: Accommodation: uid:$uid", 'realurl',-1);
        
        if (!is_numeric($params['value'])) { 
            
           $res = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query( 	'tx_chtrip_accommodation.uid,tx_chtrip_accommodation.title',
                                                                'tx_chtrip_location',	
                                                                'tx_chtrip_accommodation_mm',	
                                                                'tx_chtrip_accommodation',
                                                                ' AND tx_chtrip_location.uid='.$GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_chtrip_pi1.']['locUid'].
                                                                ' ORDER BY sorting ASC'
                                                            );
            echo $GLOBALS['TYPO3_DB']->sql_error();        
            while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
            
                $title = preg_replace($this->find,$this->replace,$row['title']);
                $list[$title] = $row['uid'];		
            }
            
            if (TYPO3_DLOG)	t3lib_div::devLog("Realurl Segment: Accommodation: list:".implode(',',$list), 'realurl',-1);
            
            $result = $list[$params['value']];            
        
        } else {
        
            $res = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query( 	'tx_chtrip_accommodation.uid,tx_chtrip_accommodation.title',
                                                                'tx_chtrip_location',	
                                                                'tx_chtrip_accommodation_mm',	
                                                                'tx_chtrip_accommodation',
                                                                ' AND tx_chtrip_location.uid='. $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_chtrip_pi1.']['locUid'].
                                                                ' ORDER BY sorting ASC'
                                                            );
            echo $GLOBALS['TYPO3_DB']->sql_error();        
            while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                $list[$row['uid']] = $row['title'];		
            }  
             
            $result = preg_replace($this->find,$this->replace,$list[$params['value']]);
        }
        
        return $result;
    }
    
}
?>