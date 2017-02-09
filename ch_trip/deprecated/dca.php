<?php

/**
 * Dynamic configuration file for the dynaflex extension
 *
 */	

$tx_chtrip_accommodation_dca = array (
												array (
													'path' => 'tx_chtrip_accommodation/types/0/showitem',
													'parseXML' => false,
													'modifications' => array (
																				array (
																					'method' => 'add',
																					'type' => 'append',
																						'config' => array (
																											'text' => ',--div--; Kategorie, category',
																									),
																					),
																			),
												),
											);
											
$tx_chtrip_location_dca = array (
											array (
												'path' => 'tx_chtrip_location/types/0/showitem',
												'parseXML' => false,
												'modifications' => array (
																			array (
																				'method' => 'add',
																				'type' => 'append',
																					'config' => array (
																										'text' => ',--div--; Objekte, numberofaccommodations;;;;1-1-1',
																								),
																				),
																		),
											),
										);


?>