<?php

require_once(t3lib_extMgm::extPath('ch_trip').'lib/class.tx_chtrip_getTree.php');

class tx_chtrip_static2 extends tx_chtrip_getTree {


		
    var $whenInfo = array ( 'from_a1' => 0,
							'till_a1' => 0,
							'from_b1' => 0,
							'till_b1' => 0,
							'from_c1' => 0,
							'till_c1' => 0,
							'from_d1' => 0,
							'till_d1' => 0,
							'from_e1' => 0,
							'till_e1' => 0,
							'from_f1' => 0,
							'till_f1' => 0,
							'from_g1' => 0,
							'till_g1' => 0,
							'from_h1' => 0,
							'till_h1' => 0,

							'from_a2' => 0,
							'till_a2' => 0,
							'from_b2' => 0,
							'till_b2' => 0,
							'from_c2' => 0,
							'till_c2' => 0,
							'from_d2' => 0,
							'till_d2' => 0,
							'from_e2' => 0,
							'till_e2' => 0,
							'from_f2' => 0,
							'till_f2' => 0,
							'from_g2' => 0,
							'till_g2' => 0,
							'from_h2' => 0,
							'till_h2' => 0,

							'from_a3' => 0,
							'till_a3' => 0,
							'from_b3' => 0,
							'till_b3' => 0,
							'from_c3' => 0,
							'till_c3' => 0,
							'from_d3' => 0,
							'till_d3' => 0,
							'from_e3' => 0,
							'till_e3' => 0,
							'from_f3' => 0,
							'till_f3' => 0,
							'from_g3' => 0,
							'till_g3' => 0,
							'from_h3' => 0,
							'till_h3' => 0,

							'from_a4' => 0,
							'till_a4' => 0,
							'from_b4' => 0,
							'till_b4' => 0,
							'from_c4' => 0,
							'till_c4' => 0,
							'from_d4' => 0,
							'till_d4' => 0,
							'from_e4' => 0,
							'till_e4' => 0,
							'from_f4' => 0,
							'till_f4' => 0,
							'from_g4' => 0,
							'till_g4' => 0,	
							'from_h4' => 0,
							'till_h4' => 0
							);			

	var $priceInfo = array ('a_baseprice' => 0,
							'b_baseprice' => 0,
							'c_baseprice' => 0,
							'd_baseprice' => 0,
							'e_baseprice' => 0,
							'f_baseprice' => 0,
							'g_baseprice' => 0,
							'h_baseprice' => 0
							);
									
	var $halfboard = array ('a_halfboard' => 0,
							'b_halfboard' => 0,
							'c_halfboard' => 0,
							'd_halfboard' => 0,
							'e_halfboard' => 0,
							'f_halfboard' => 0,
							'g_halfboard' => 0,
							'h_halfboard' => 0
							);

	 var $altWhenInfo = array ( 'alt_from_a1' => 0,
								'alt_till_a1' => 0,
								'alt_from_b1' => 0,
								'alt_till_b1' => 0,
								'alt_from_c1' => 0,
								'alt_till_c1' => 0,
								'alt_from_d1' => 0,
								'alt_till_d1' => 0,
								'alt_from_e1' => 0,
								'alt_till_e1' => 0,
								'alt_from_f1' => 0,
								'alt_till_f1' => 0,
								'alt_from_g1' => 0,
								'alt_till_g1' => 0,
								'alt_from_h1' => 0,
								'alt_till_h1' => 0,
	
								'alt_from_a2' => 0,
								'alt_till_a2' => 0,
								'alt_from_b2' => 0,
								'alt_till_b2' => 0,
								'alt_from_c2' => 0,
								'alt_till_c2' => 0,
								'alt_from_d2' => 0,
								'alt_till_d2' => 0,
								'alt_from_e2' => 0,
								'alt_till_e2' => 0,
								'alt_from_f2' => 0,
								'alt_till_f2' => 0,
								'alt_from_g2' => 0,
								'alt_till_g2' => 0,
								'alt_from_h2' => 0,
								'alt_till_h2' => 0,
	
								'alt_from_a3' => 0,
								'alt_till_a3' => 0,
								'alt_from_b3' => 0,
								'alt_till_b3' => 0,
								'alt_from_c3' => 0,
								'alt_till_c3' => 0,
								'alt_from_d3' => 0,
								'alt_till_d3' => 0,
								'alt_from_e3' => 0,
								'alt_till_e3' => 0,
								'alt_from_f3' => 0,
								'alt_till_f3' => 0,
								'alt_from_g3' => 0,
								'alt_till_g3' => 0,
								'alt_from_h3' => 0,
								'alt_till_h3' => 0,
	
								'alt_from_a4' => 0,
								'alt_till_a4' => 0,
								'alt_from_b4' => 0,
								'alt_till_b4' => 0,
								'alt_from_c4' => 0,
								'alt_till_c4' => 0,
								'alt_from_d4' => 0,
								'alt_till_d4' => 0,
								'alt_from_e4' => 0,
								'alt_till_e4' => 0,
								'alt_from_f4' => 0,
								'alt_till_f4' => 0,
								'alt_from_g4' => 0,
								'alt_till_g4' => 0,	
								'alt_from_h4' => 0,
								'alt_till_h4' => 0
								);			

	var $altPriceInfo = array ( 'alt_a_baseprice' => 0,
								'alt_b_baseprice' => 0,
								'alt_c_baseprice' => 0,
								'alt_d_baseprice' => 0,
								'alt_e_baseprice' => 0,
								'alt_f_baseprice' => 0,
								'alt_g_baseprice' => 0,
								'alt_h_baseprice' => 0
								);
									
	var $altHalfboard = array (	'alt_a_halfboard' => 0,
								'alt_b_halfboard' => 0,
								'alt_c_halfboard' => 0,
								'alt_d_halfboard' => 0,
								'alt_e_halfboard' => 0,
								'alt_f_halfboard' => 0,
								'alt_g_halfboard' => 0,
								'alt_h_halfboard' => 0
							);

}


?>