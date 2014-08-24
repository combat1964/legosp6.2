<?php
/*****************************************************************************
*                                                                           *
* Lego SP - lego.shop-script.org                                            *
* Copyright (c) 2012 Sergey Piekhota. All rights reserved.                  *
*                                                                           *
****************************************************************************/

	// show whole price list

	if (isset($_GET["show_price"])) //show pricelist
	{
		//calculate a path
		$path = Array();
                if (CONF_CHPU) $row[0] = "./pricelist/";
	        else $row[0] = 'index.php?show_price=yes';
                
		
		$row[1] = STRING_PRICELIST;
		$path[] = $row;

		$smarty->assign("product_category_path",$path);

		$pricelist_elements = pricessCategories(0, 0);


		if (CONF_PRICE_SHOW_COUNT == 0) {$price_config[0] = 60;} else {$price_config[0] = CONF_PRICE_SHOW_COUNT;}
		$price_config[1] = ceil(count($pricelist_elements)/$price_config[0]);
		if (isset($_GET["show_price"])) {$price_config[2] = $_GET["show_price"];} else {$price_config[2] = 0;}
		$price_config[3] = $price_config[2] * $price_config[0];

		$smarty->assign("pricelist_elements", $pricelist_elements);
		$smarty->assign("pricelist_cnf", $price_config);
		$smarty->assign("main_content_template", "pricelist.tpl.html");
	}

?>