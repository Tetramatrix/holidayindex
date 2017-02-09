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
 
class tx_chtrip_metatags  {

    function title() {

        $this->lConf = $this->getExtConf(intval($GLOBALS['TSFE']->id));
 
        # Do query array		
        $this->doObjArray();

        if ($this->piVars['scroll']) { 
           
            # Do search
            $finds = $this->find($this->piVars,false);
            if (sizeOf($finds)>0) {
                $finds = $this->bubbleSort($finds,1);
                $finds = $this->indexFinds($finds);
                $finds = $this->filterFinds($this->piVars,$finds);
            }
            if (sizeOf($finds)>0) {        
                $this->totalFinds = sizeOf($finds);
    
                # Merge finds with regions
                $tree = $this->mergeFindsRegions($finds);            
            }
    
            unset($this->piVars['uid']);	
            switch ($piVars['scroll']) {
                case 'prev':
                    $this->piVars['item'] -= 1;
                    $this->findItem($tree,$this->piVars['item']);
                    $this->piVars['uid'] = $this->treeUid;                        
                break;
                case 'next':
                    $this->piVars['item'] += 1;
                    $this->findItem($tree,$this->piVars['item']);
                    $this->piVars['uid'] = $this->treeUid;					
                break;				
            }		
        }

        if (intval($this->piVars['uid'])) {
            
            # Get type icon
            $type = $this->getObjType(intval($this->piVars['uid']));
    
            # Get Title
            $loc = $this->getTitle(intval($this->piVars['uid']));
                            
            $content = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_chtrip_pi1.']['browserTitle'].' '.addslashes($type['title']).' '.$loc['title'];
                   
        } else {
        
            $content = 'Urlaub in Italien: Ferienwohnungen, Ferienhaus, Hausboote - Unterk�nfte vom Italien Insider amici d`Italia';
  
        }
        
        return $content;
    }
    
    
    function description() {
            
        if ($this->piVars['scroll']) {
           
            $this->lConf = $this->getExtConf(intval($GLOBALS['TSFE']->id));
            
            # Do query array		
            $this->doObjArray();

            # Do search
            $finds = $this->find($this->piVars,false);
            if (sizeOf($finds)>0) {
                $finds = $this->bubbleSort($finds,1);
                $finds = $this->indexFinds($finds);
                $finds = $this->filterFinds($this->piVars,$finds);
            }
            if (sizeOf($finds)>0) {        
                $this->totalFinds = sizeOf($finds);
    
                # Merge finds with regions
                $tree = $this->mergeFindsRegions($finds);            
            }
    
            unset($this->piVars['uid']);        
            switch ($this->piVars['scroll']) {
                case 'prev':
                    $this->piVars['item'] -= 1;
                    $this->findItem($tree,$this->piVars['item']);
                    $piVars['uid'] = $this->treeUid;                            
                break;
                case 'next':
                    $this->piVars['item'] += 1;
                    $this->findItem($tree,$this->piVars['item']);
                    $this->piVars['uid'] = $this->treeUid;
                break;				
            }		
        }	
        
        $loc = $this->getTeaser(intval($this->piVars['uid']));
        
        $content = '';
        
        $head = '   <meta name="description" content="Ob Ferienhaus, Ferienwohnung, Hotel: amici d italia ist ihr Spezialist f�r Ferienwohnungen, Ferienh�user in Italien, Toskana, Venedig, Sizilien, Apulien, Latium, Rom, Umbrien, Emilia Romagna, Kampanien" />
                    <meta name="keywords" content="Italien Ferienwohnung,Ferienwohnung Italien,Italien Ferienhaus,Toskana Ferienwohnung,Toskana Ferienhaus,Toscana Ferienwohnung,Toscana Ferienhaus,Venedig Ferienwohnung,Venedig Ferienhaus,Sizilien Ferienwohnung,sizilien Ferienhaus,Apulien Ferienwohnung,Latium Ferienwohnung,Latium Ferienhaus,Umbrien Ferienwohnung,Umbrien Ferienhaus,Adria Ferienwohnung,Adria FerienhausKampanien Ferienwohnung,Kampanien Ferienhaus,Urlaub,Italien,Hausboote,Hotel" />
                    <meta name="robots" content="all" />
                    <meta name="copyright" content="(c) 2006 by amici d Italia" />
                    <meta http-equiv="content-language" content="de" />
                    <meta name="author" content="amici d Italia" />
                    <meta name="distribution" content="Global" />
                    <meta name="rating" content="General" />
                    <meta name="revisit-after" content="14" />
                    <meta name="DC.Description" content="Ob Ferienhaus, Ferienwohnung, Hotel: amici d italia ist ihr Spezialist f�r Ferienwohnungen, Ferienh�user in Italien, Toskana, Venedig, Sizilien, Apulien, Latium, Rom, Umbrien, Emilia Romagna, Kampanien" />
                    <meta name="DC.Subject" content="Italien Ferienwohnung,Ferienwohnung Italien,Italien Ferienhaus,Toskana Ferienwohnung,Toskana Ferienhaus,Toscana Ferienwohnung,Toscana Ferienhaus,Venedig Ferienwohnung,Venedig Ferienhaus,Sizilien Ferienwohnung,sizilien Ferienhaus,Apulien Ferienwohnung,Latium Ferienwohnung,Latium Ferienhaus,Umbrien Ferienwohnung,Umbrien Ferienhaus,Adria Ferienwohnung,Adria FerienhausKampanien Ferienwohnung,Kampanien Ferienhaus,Urlaub,Italien,Hausboote,Hotel" />
                    <meta name="DC.Rights" content="(c) 2006 by amici d Italia" />
                    <meta name="DC.Language" scheme="NISOZ39.50" content="de" />
                    <meta name="DC.Creator" content="amici d Italia" />
                    <link rel="schema.dc" href="http://purl.org/metadata/dublin_core_elements" />
                    <meta name="page-topic" content="Reisen" /><meta name="page-type" content="Reisekatalog" />
                    
                ';

        $flag = true;
        
        if (!empty($loc['teaser'])) {
        
            $flag = false;
            
            $content = '<meta name="description" Content="'.htmlentities($loc['teaser']).'">';
        }
        
        if (!empty($loc['metakeywords'])) {
        
            $flag = false;
            
            $content .= '<meta name="keywords" Content="'.htmlentities($loc['metakeywords']).'">';
            
        }        
        
        if ($flag) {
        
            $content = $head;
        }
     
        return $content;    
    }
}


?>