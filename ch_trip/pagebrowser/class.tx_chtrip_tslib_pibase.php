<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2005-2008 Chi Hoang
*  All rights reserved
*
***************************************************************/

if (!defined ('PATH_tslib')) {
	define('PATH_tslib', '../../../../../tslib/');
}

require_once(PATH_tslib.'class.tslib_pibase.php');


class tx_chtrip_tslib_pibase extends tslib_pibase {

    
    var $ter_pointer;
    
    var $internal = Array(	// Used internally for general storage of values between methods
		'res_count' => 0,		// Total query count
		'results_at_a_time' => 0,	// pi_list_browseresults(): Show number of results at a time
		'maxPages' => 0,		// pi_list_browseresults(): Max number of 'Page 1 - Page 2 - ...' in the list browser
		'currentRow' => Array(),	// Current result row
		'currentTable' => '',		// Current table
		'showFirstLast' => 1
	);

	function tslib_pibase()	{

	}    

   	/**
	 * Makes a standard query for listing of records based on standard input vars from the 'browser' ($this->internal['results_at_a_time'] and $this->piVars['pointer']) and 'searchbox' ($this->piVars['sword'] and $this->internal['searchFieldList'])
	 * Set $count to 1 if you wish to get a count(*) query for selecting the number of results.
	 * Notice that the query will use $this->conf['pidList'] and $this->conf['recursive'] to generate a PID list within which to search for records.
	 *
	 * @param	string		See pi_exec_query()
	 * @param	boolean		See pi_exec_query()
	 * @param	string		See pi_exec_query()
	 * @param	mixed		See pi_exec_query()
	 * @param	string		See pi_exec_query()
	 * @param	string		See pi_exec_query()
	 * @param	string		See pi_exec_query()
	 * @param	boolean		If set, the function will return the query not as a string but array with the various parts.
	 * @return	mixed		The query build.
	 * @access private
	 * @deprecated		Use pi_exec_query() instead!
	 */
	function pi_list_query($table,$count=0,$addWhere='',$mm_cat='',$groupBy='',$orderBy='',$query='',$returnQueryArray=FALSE)	{

    			// Begin Query:
		if (!$query)	{
				// Fetches the list of PIDs to select from.
				// TypoScript property .pidList is a comma list of pids. If blank, current page id is used.
				// TypoScript property .recursive is a int+ which determines how many levels down from the pids in the pid-list subpages should be included in the select.
			$pidList = 2;
            
			if (is_array($mm_cat))	{
				$query='FROM '.$table.','.$mm_cat['table'].','.$mm_cat['mmtable'].chr(10).
						' WHERE '.$table.'.uid='.$mm_cat['mmtable'].'.uid_local AND '.$mm_cat['table'].'.uid='.$mm_cat['mmtable'].'.uid_foreign '.chr(10).
						(strcmp($mm_cat['catUidList'],'')?' AND '.$mm_cat['table'].'.uid IN ('.$mm_cat['catUidList'].')':'').chr(10).
						' AND '.$table.'.pid IN ('.$pidList.')';
			} else {
				$query='FROM '.$table.' WHERE pid IN ('.$pidList.')';
			}
		}

			// Split the "FROM ... WHERE" string so we get the WHERE part and TABLE names separated...:
		list($TABLENAMES,$WHERE) = spliti('WHERE', trim($query), 2);
		$TABLENAMES = trim(substr(trim($TABLENAMES),5));
		$WHERE = trim($WHERE);

			// Add '$addWhere'
		if ($addWhere)	{$WHERE.=' '.$addWhere.chr(10);}

			// Search word:
		if ($this->piVars['sword'] && $this->internal['searchFieldList'])	{
			$WHERE.=$this->cObj->searchWhere($this->piVars['sword'],$this->internal['searchFieldList'],$table).chr(10);
		}

		if ($count) {
			$queryParts = array(
				'SELECT' => 'count(*)',
				'FROM' => $TABLENAMES,
				'WHERE' => $WHERE,
				'GROUPBY' => '',
				'ORDERBY' => '',
				'LIMIT' => ''
			);
		} else {
				// Order by data:
			if (!$orderBy && $this->internal['orderBy'])	{
				if (t3lib_div::inList($this->internal['orderByList'],$this->internal['orderBy']))	{
					$orderBy = 'ORDER BY '.$table.'.'.$this->internal['orderBy'].' '.$this->internal['descFlag'];
				}
			}

				// Limit data:
			$pointer = intval($this->ter_pointer);
			
            		$results_at_a_time = t3lib_div::intInRange($this->internal['results_at_a_time'],1,1000);
			$LIMIT = ($pointer*$results_at_a_time).','.$results_at_a_time;

				// Add 'SELECT'
			$queryParts = array(
				'SELECT' => $this->pi_listFields,
				'FROM' => $TABLENAMES,
				'WHERE' => $WHERE,
				'GROUPBY' => $GLOBALS['TYPO3_DB']->stripGroupBy($groupBy),
				'ORDERBY' => $GLOBALS['TYPO3_DB']->stripOrderBy($orderBy),
				'LIMIT' => $LIMIT
			);
		}

		$query = $GLOBALS['TYPO3_DB']->SELECTquery (
					$queryParts['SELECT'],
					$queryParts['FROM'],
					$queryParts['WHERE'],
					$queryParts['GROUPBY'],
					$queryParts['ORDERBY'],
					$queryParts['LIMIT']
				);
		return $returnQueryArray ? $queryParts : $query;
	}
    
    	/**
	 * Link string to the current page.
	 * Returns the $str wrapped in <a>-tags with a link to the CURRENT page, but with $urlParameters set as extra parameters for the page.
	 *
	 * @param	string		The content string to wrap in <a> tags
	 * @param	array		Array with URL parameters as key/value pairs. They will be "imploded" and added to the list of parameters defined in the plugins TypoScript property "parent.addParams" plus $this->pi_moreParams.
	 * @param	boolean		If $cache is set (0/1), the page is asked to be cached by a &cHash value (unless the current plugin using this class is a USER_INT). Otherwise the no_cache-parameter will be a part of the link.
	 * @param	integer		Alternative page ID for the link. (By default this function links to the SAME page!)
	 * @return	string		The input string wrapped in <a> tags
	 * @see pi_linkTP_keepPIvars(), tslib_cObj::typoLink()
	 */
	function pi_linkTP($str,$urlParameters=array(),$cache=0,$altPageId=0)	{
		$conf=array();
		$conf['useCacheHash'] = $this->pi_USER_INT_obj ? 0 : $cache;
		$conf['no_cache'] = $this->pi_USER_INT_obj ? 0 : !$cache;
		$conf['parameter'] = $altPageId ? $altPageId : ($this->pi_tmpPageId ? $this->pi_tmpPageId : $GLOBALS['TSFE']->id);
		$conf['additionalParams'] = $this->conf['parent.']['addParams'].t3lib_div::implodeArrayForUrl('',$urlParameters,'',1).$this->pi_moreParams;

		$cmd = 'ter_connect='.t3lib_div::_GP('ter_connect');
		$cmd .= '&ter_search='.t3lib_div::_GP('ter_search');        
		$cmd .= '&ter_sort='.t3lib_div::_GP('ter_sort');        
		$cmd .= '&ter_order='.t3lib_div::_GP('ter_order');        
		$cmd .= '&ter_pointer='.t3lib_div::_GP('ter_pointer');        
		$cmd .= '&t9r_backup='.t3lib_div::_GP('t9r_backup');        
        
        return '<a onMouseover=highlight(this,\'yellow\') onMouseout="highlight(this,document.bgColor)" href="index.php?'.$cmd.$conf['additionalParams'].'">'.$str.'</a>';
	}
    
    	/**
	 * Link a string to the current page while keeping currently set values in piVars.
	 * Like pi_linkTP, but $urlParameters is by default set to $this->piVars with $overrulePIvars overlaid.
	 * This means any current entries from this->piVars are passed on (except the key "DATA" which will be unset before!) and entries in $overrulePIvars will OVERRULE the current in the link.
	 *
	 * @param	string		The content string to wrap in <a> tags
	 * @param	array		Array of values to override in the current piVars. Contrary to pi_linkTP the keys in this array must correspond to the real piVars array and therefore NOT be prefixed with the $this->prefixId string. Further, if a value is a blank string it means the piVar key will not be a part of the link (unset)
	 * @param	boolean		If $cache is set, the page is asked to be cached by a &cHash value (unless the current plugin using this class is a USER_INT). Otherwise the no_cache-parameter will be a part of the link.
	 * @param	boolean		If set, then the current values of piVars will NOT be preserved anyways... Practical if you want an easy way to set piVars without having to worry about the prefix, "tx_xxxxx[]"
	 * @param	integer		Alternative page ID for the link. (By default this function links to the SAME page!)
	 * @return	string		The input string wrapped in <a> tags
	 * @see pi_linkTP()
	 */
	function pi_linkTP_keepPIvars($str,$overrulePIvars=array(),$cache=0,$clearAnyway=0,$altPageId=0)	{
		if (is_array($this->piVars) && is_array($overrulePIvars) && !$clearAnyway)	{
			$piVars = $this->piVars;
			unset($piVars['DATA']);
			$overrulePIvars = t3lib_div::array_merge_recursive_overrule($piVars,$overrulePIvars);
			if ($this->pi_autoCacheEn)	{
				$cache = $this->pi_autoCache($overrulePIvars);
			}
		}
		$res = $this->pi_linkTP($str,Array($this->prefixId=>$overrulePIvars),$cache,$altPageId);
		return $res;
	}
    
    
	/***************************
	 *
	 * Functions for listing, browsing, searching etc.
	 *
	 **************************/

	/**
	 * Returns a results browser. This means a bar of page numbers plus a "previous" and "next" link. For each entry in the bar the piVars "pointer" will be pointing to the "result page" to show.
	 * Using $this->piVars['pointer'] as pointer to the page to display. Can be overwritten with another string ($pointerName) to make it possible to have more than one pagebrowser on a page)
	 * Using $this->internal['res_count'], $this->internal['results_at_a_time'] and $this->internal['maxPages'] for count number, how many results to show and the max number of pages to include in the browse bar.
	 * Using $this->internal['dontLinkActivePage'] as switch if the active (current) page should be displayed as pure text or as a link to itself
	 * Using $this->internal['showFirstLast'] as switch if the two links named "<< First" and "LAST >>" will be shown and point to the first or last page.
	 * Using $this->internal['pagefloat']: this defines were the current page is shown in the list of pages in the Pagebrowser. If this var is an integer it will be interpreted as position in the list of pages. If its value is the keyword "center" the current page will be shown in the middle of the pagelist.
	 * Using $this->internal['showRange']: this var switches the display of the pagelinks from pagenumbers to ranges f.e.: 1-5 6-10 11-15... instead of 1 2 3...
	 * Using $this->pi_isOnlyFields: this holds a comma-separated list of fieldnames which - if they are among the GETvars - will not disable caching for the page with pagebrowser.
	 *
	 * The third parameter is an array with several wraps for the parts of the pagebrowser. The following elements will be recognized:
	 * disabledLinkWrap, inactiveLinkWrap, activeLinkWrap, browseLinksWrap, showResultsWrap, showResultsNumbersWrap, browseBoxWrap.
	 *
	 * If $wrapArr['showResultsNumbersWrap'] is set, the formatting string is expected to hold template markers (###FROM###, ###TO###, ###OUT_OF###, ###FROM_TO###, ###CURRENT_PAGE###, ###TOTAL_PAGES###)
	 * otherwise the formatting sting is expected to hold sprintf-markers (%s) for from, to, outof (in that sequence)
	 *
	 * 										otherwise wrapping is totally controlled/modified by this array
	 *
	 * @param	integer		determines how the results of the pagerowser will be shown. See description below
	 * @param	string		Attributes for the table tag which is wrapped around the table cells containing the browse links
	 * @param	array		Array with elements to overwrite the default $wrapper-array.
	 * @param	string		varname for the pointer.
	 * @param	boolean		enable htmlspecialchars() for the pi_getLL function (set this to FALSE if you want f.e use images instead of text for links like 'previous' and 'next').
	 * @return	string		Output HTML-Table, wrapped in <div>-tags with a class attribute (if $wrapArr is not passed,
	 */
	function pi_list_browseresults($showResultCount=1,$tableParams='',$wrapArr=array(), $pointerName = 'ter_pointer', $hscText = TRUE)	{

		// example $wrapArr-array how it could be traversed from an extension
		/* $wrapArr = array(
			'browseBoxWrap' => '<div class="browseBoxWrap">|</div>',
			'showResultsWrap' => '<div class="showResultsWrap">|</div>',
			'browseLinksWrap' => '<div class="browseLinksWrap">|</div>',
			'showResultsNumbersWrap' => '<span class="showResultsNumbersWrap">|</span>',
			'disabledLinkWrap' => '<span class="disabledLinkWrap">|</span>',
			'inactiveLinkWrap' => '<span class="inactiveLinkWrap">|</span>',
			'activeLinkWrap' => '<span class="activeLinkWrap">|</span>'
		); */

			// Initializing variables:
		$pointer = intval($this->ter_pointer);        
		$count = intval($this->internal['res_count']);		
        	$results_at_a_time = t3lib_div::intInRange($this->internal['results_at_a_time'],1,1000);
		$totalPages = ceil($count/$results_at_a_time);

		$maxPages = t3lib_div::intInRange($this->internal['maxPages'],1,100);
		$pi_isOnlyFields = $this->pi_isOnlyFields($this->pi_isOnlyFields);

			// $showResultCount determines how the results of the pagerowser will be shown.
			// If set to 0: only the result-browser will be shown
			//	 		 1: (default) the text "Displaying results..." and the result-browser will be shown.
			//	 		 2: only the text "Displaying results..." will be shown
		$showResultCount = intval($showResultCount);

			// if this is set, two links named "<< First" and "LAST >>" will be shown and point to the very first or last page.
		$showFirstLast = $this->internal['showFirstLast'];

			// if this has a value the "previous" button is always visible (will be forced if "showFirstLast" is set)
		$alwaysPrev = $showFirstLast?1:$this->pi_alwaysPrev;

		if (isset($this->internal['pagefloat'])) {
			if (strtoupper($this->internal['pagefloat']) == 'CENTER') {
				$pagefloat = ceil(($maxPages - 1)/2);
			} else {
				// pagefloat set as integer. 0 = left, value >= $this->internal['maxPages'] = right
				$pagefloat = t3lib_div::intInRange($this->internal['pagefloat'],-1,$maxPages-1);
			}
		} else {
			$pagefloat = -1; // pagefloat disabled
		}

			// default values for "traditional" wrapping with a table. Can be overwritten by vars from $wrapArr
		$wrapper['disabledLinkWrap'] = '<td nowrap="nowrap"><p>|</p></td>';
		$wrapper['inactiveLinkWrap'] = '<td nowrap="nowrap"><p>|</p></td>';
		$wrapper['activeLinkWrap'] = '<td'.$this->pi_classParam('browsebox-SCell').' nowrap="nowrap"><p>|</p></td>';
		$wrapper['browseLinksWrap'] = trim('<table '.$tableParams).'><tr>|</tr></table>';
		$wrapper['showResultsWrap'] = '<p>|</p>';
		$wrapper['browseBoxWrap'] = '
		<!--
			List browsing box:
		-->
		<div '.$this->pi_classParam('browsebox').'>
			|
		</div>';
				
			// now overwrite all entries in $wrapper which are also in $wrapArr
		$wrapper = array_merge($wrapper,$wrapArr);

		if ($showResultCount != 2) { //show pagebrowser
			if ($pagefloat > -1) {
				$lastPage = min($totalPages,max($pointer+1 + $pagefloat,$maxPages));
				$firstPage = max(0,$lastPage-$maxPages);
			} else {
				$firstPage = 0;
				$lastPage = t3lib_div::intInRange($totalPages,1,$maxPages);
			}
			$links=array();

				// Make browse-table/links:
			if ($showFirstLast) { // Link to first page
				if ($pointer>0)	{
					$links[]=$this->cObj->wrap($this->pi_linkTP_keepPIvars($this->pi_getLL('pi_list_browseresults_first','<< First',$hscText),array($pointerName => 0),$pi_isOnlyFields),$wrapper['inactiveLinkWrap']);
				} else {
					$links[]=$this->cObj->wrap($this->pi_getLL('pi_list_browseresults_first','<< First',$hscText),$wrapper['disabledLinkWrap']);
				}
			}
			if ($alwaysPrev>=0)	{ // Link to previous page
				if ($pointer>0)	{
					$links[]=$this->cObj->wrap($this->pi_linkTP_keepPIvars($this->pi_getLL('pi_list_browseresults_prev','< Previous',$hscText),array($pointerName => ($pointer-1?$pointer-1:'0')),$pi_isOnlyFields),$wrapper['inactiveLinkWrap']);
				} elseif ($alwaysPrev)	{
					$links[]=$this->cObj->wrap($this->pi_getLL('pi_list_browseresults_prev','< Previous',$hscText),$wrapper['disabledLinkWrap']);
				}
			}
			for($a=$firstPage;$a<$lastPage;$a++)	{ // Links to pages
				if ($this->internal['showRange']) {
					$pageText = (($a*$results_at_a_time)+1).'-'.min($count,(($a+1)*$results_at_a_time));
				} else {
					$pageText = trim($this->pi_getLL('pi_list_browseresults_page','Page',$hscText).' '.($a+1));
				}
				if ($pointer == $a) { // current page
					if ($this->internal['dontLinkActivePage']) {
						$links[] = $this->cObj->wrap($pageText,$wrapper['activeLinkWrap']);
					} else {
						$links[] = $this->cObj->wrap($this->pi_linkTP_keepPIvars($pageText,array($pointerName  => ($a?$a:'0')),$pi_isOnlyFields),$wrapper['activeLinkWrap']);
					}
				} else {
					$links[] = $this->cObj->wrap($this->pi_linkTP_keepPIvars($pageText,array($pointerName => ($a?$a:'0')),$pi_isOnlyFields),$wrapper['inactiveLinkWrap']);
				}
			}
			if ($pointer<$totalPages-1 || $showFirstLast)	{
				if ($pointer==$totalPages-1) { // Link to next page
					$links[]=$this->cObj->wrap($this->pi_getLL('pi_list_browseresults_next','Next >',$hscText),$wrapper['disabledLinkWrap']);
				} else {
					$links[]=$this->cObj->wrap($this->pi_linkTP_keepPIvars($this->pi_getLL('pi_list_browseresults_next','Next >',$hscText),array($pointerName => $pointer+1),$pi_isOnlyFields),$wrapper['inactiveLinkWrap']);
				}
			}
			if ($showFirstLast) { // Link to last page
				if ($pointer<$totalPages-1) {
					$links[]=$this->cObj->wrap($this->pi_linkTP_keepPIvars($this->pi_getLL('pi_list_browseresults_last','Last >>',$hscText),array($pointerName => $totalPages-1),$pi_isOnlyFields),$wrapper['inactiveLinkWrap']);
				} else {
					$links[]=$this->cObj->wrap($this->pi_getLL('pi_list_browseresults_last','Last >>',$hscText),$wrapper['disabledLinkWrap']);
				}
			}
			$theLinks = $this->cObj->wrap(implode(chr(10),$links),$wrapper['browseLinksWrap']);
		} else {
			$theLinks = '';
		}

		$pR1 = $pointer*$results_at_a_time+1;
		$pR2 = $pointer*$results_at_a_time+$results_at_a_time;

		if ($showResultCount) {
			if ($wrapper['showResultsNumbersWrap']) {
				// this will render the resultcount in a more flexible way using markers (new in TYPO3 3.8.0).
				// the formatting string is expected to hold template markers (see function header). Example: 'Displaying results ###FROM### to ###TO### out of ###OUT_OF###'

				$markerArray['###FROM###'] = $this->cObj->wrap($this->internal['res_count'] > 0 ? $pR1 : 0,$wrapper['showResultsNumbersWrap']);
				$markerArray['###TO###'] = $this->cObj->wrap(min($this->internal['res_count'],$pR2),$wrapper['showResultsNumbersWrap']);
				$markerArray['###OUT_OF###'] = $this->cObj->wrap($this->internal['res_count'],$wrapper['showResultsNumbersWrap']);
				$markerArray['###FROM_TO###'] = $this->cObj->wrap(($this->internal['res_count'] > 0 ? $pR1 : 0).' '.$this->pi_getLL('pi_list_browseresults_to','to').' '.min($this->internal['res_count'],$pR2),$wrapper['showResultsNumbersWrap']);
				$markerArray['###CURRENT_PAGE###'] = $this->cObj->wrap($pointer+1,$wrapper['showResultsNumbersWrap']);
				$markerArray['###TOTAL_PAGES###'] = $this->cObj->wrap($totalPages,$wrapper['showResultsNumbersWrap']);
				// substitute markers
				$resultCountMsg = $this->cObj->substituteMarkerArray($this->pi_getLL('pi_list_browseresults_displays','Displaying results ###FROM### to ###TO### out of ###OUT_OF###'),$markerArray);
			} else {
				// render the resultcount in the "traditional" way using sprintf
				$resultCountMsg = sprintf(
					str_replace('###SPAN_BEGIN###','<span style="background-color:#FFFF00" '.$this->pi_classParam('browsebox-strong').'>',$this->pi_getLL('pi_list_browseresults_displays','Displaying results ###SPAN_BEGIN###%s to %s</span> out of ###SPAN_BEGIN###%s</span>')),
					$count > 0 ? $pR1 : 0,
					min($count,$pR2),
					$count);
			}
			$resultCountMsg = $this->cObj->wrap($resultCountMsg,$wrapper['showResultsWrap']);
			
		} else {
			$resultCountMsg = '';
		}

		$sTables = $this->cObj->wrap($theLinks.$resultCountMsg,$wrapper['browseBoxWrap']);

		return $sTables;
	}

}

?>