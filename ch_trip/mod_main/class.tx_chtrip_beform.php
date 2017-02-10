<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005-2008 Chi Hoang
*  All rights reserved
*
***************************************************************/

if (!defined ('PATH_txva')) {
	define('PATH_txva', t3lib_extMgm::extPath('ch_trip'));
}

require_once(PATH_txva.'mod_main/class.tx_chtrip_treeView.php');


class tx_chtrip_beform {

	var $doc;
	var $content;
	var $hiddenMenu;

		// Internal, static: _GP
	var $currentSubScript;

	var $mainModule ='';


		// Constructor:
	function init()	{
		global $AB,$BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$HTTP_GET_VARS,$HTTP_POST_VARS,$CLIENT,$TYPO3_CONF_VARS;

		$this->currentSubScript = t3lib_div::_GP('currentSubScript');

			// Setting highlight mode:
		$this->doHighlight = !$BE_USER->getTSConfigVal('options.pageTree.disableTitleHighlight');

			// the trees
		$this->view = t3lib_div::makeInstance('tx_chtrip_treeView');
		$this->view->init(t3lib_div::getIndpEnv('SCRIPT_NAME'),'elbrowser');

		$this->doc = t3lib_div::makeInstance('template');
		$this->doc->backPath = $BACK_PATH;
		
		$tmp = $this->doc->getContextMenuCode();
		$this->hiddenMenu = $tmp[2];
		$this->doc->bodyTagAdditions = $tmp[1];

			// clear JS
		$this->doc->JScode='';

			// Setting JavaScript for menu.
		$this->doc->JScode=$tmp[0].$this->doc->wrapScriptTags(
			($this->currentSubScript?'top.currentSubScript=unescape("'.rawurlencode($this->currentSubScript).'");':'').'

			function jumpTo(params,linkObj,highLightID)	{
				var theUrl = top.TS.PATH_typo3+top.currentSubScript+"?"+params;

				if (top.condensedMode)	{
					top.content.document.location=theUrl;
				} else {
					parent.list_frame.document.location=theUrl;
				}
				'.($this->doHighlight?'hilight_row("row"+top.fsMod.recentIds["'.$this->mainModule.'"],highLightID);':'').'
				'.(!$GLOBALS['CLIENT']['FORMSTYLE'] ? '' : 'if (linkObj) {linkObj.blur();}').'
				return false;
			}
				// Call this function, refresh_nav(), from another script in the backend if you want to refresh the navigation frame (eg. after having changed a page title or moved pages etc.)
				// See t3lib_BEfunc::getSetUpdateSignal()
			function refresh_nav()	{
				window.setTimeout("_refresh_nav();",0);
			}

				/**
				* [Describe function...]
				* 
				* @return	[t]		...
				*/
			function _refresh_nav()	{
				document.location="'.htmlspecialchars(t3lib_div::getIndpEnv('SCRIPT_NAME').'?unique='.time()).'";
			}

				// Highlighting rows in the page tree:
			function hilight_row(frameSetModule,highLightID) {	//

					// Remove old:
				theObj = document.getElementById(top.fsMod.navFrameHighlightedID[frameSetModule]);
				if (theObj)	{
					theObj.style.backgroundColor="";
				}

					// Set new:
				top.fsMod.navFrameHighlightedID[frameSetModule] = highLightID;
				theObj = document.getElementById(highLightID);
				if (theObj)	{
					theObj.style.backgroundColor="#d0e4c9";
				}
			}
		');

			// should be float but gives bad results
		$this->doc->inDocStyles .= '
			.txdam-editbar, .txdam-editbar > a >img {
				background-color:'.t3lib_div::modifyHTMLcolor($this->doc->bgColor,-15,-15,-15).';
			}
			';
	}




	/**
	 * Main function, rendering the browsable page tree
	 * 
	 * @return	void		
	 */
	function main()	{
		global $LANG,$BACK_PATH;

		$this->content = '';

		$this->content.= $this->doc->startPage('Navigation');

		$this->content.= $this->hiddenMenu;

		$this->content.= $this->view->getTrees();

		$temp=preg_replace('/\?.+/','',t3lib_div::_GP('tree'));
		
		$this->content.= '
			<p class="c-refresh">
				<a href="'.htmlspecialchars(t3lib_div::getIndpEnv('SCRIPT_NAME').'?tree='.$temp).'">'.
				'<img'.t3lib_iconWorks::skinImg($BACK_PATH,'gfx/refresh_n.gif','width="14" height="14"').' title="'.$LANG->sL('LLL:EXT:lang/locallang_core.php:labels.refresh',1).'" alt="" />'.
				$LANG->sL('LLL:EXT:lang/locallang_core.php:labels.refresh',1).'</a>
			</p>
			<br />';

			// Adding highlight - JavaScript
		if ($this->doHighlight)	$this->content .=$this->doc->wrapScriptTags('
			hilight_row("",top.fsMod.navFrameHighlightedID["web"]);
		');
	}


	/**
	 * Outputting the accumulated content to screen
	 * 
	 * @return	void		
	 */
	function printContent()	{
		$this->content.= $this->doc->endPage();
		echo $this->content;
	}

}

// Include extension?
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ch_trip/mod_main/class.tx_chtrip_beform.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ch_trip/mod_main/class.tx_chtrip_beform.php']);
}

?>