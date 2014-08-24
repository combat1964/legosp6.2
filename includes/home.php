<?php
/*****************************************************************************
 *                                                                           *
 * Lego SP - legosp.net                                                      *
 * Copyright (c) 2012 Sergey Piekhota. All rights reserved.                  * 
 *                                                                           *
 ****************************************************************************/
	// front-end homepage
    if (!$categoryID && !isset($productID))
    {   
        $f = file("./core/aux_pages/index");
	        $out = implode("", $f);
        if (DB_CHARSET!='cp1251') $out=win2utf($out);   
	$smarty->assign("index", $out);  
	        
	//special offers
	$result = array();
        $sql='SELECT pt.productID, pt.name, pt.thumbnail picture, Price,list_price, brief_description, pt.hurl FROM '.SPECIAL_OFFERS_TABLE." as st join ".PRODUCTS_TABLE." as pt on (pt.productID=st.productID and pt.categoryID>0 and pt.enabled=1) join ".CATEGORIES_TABLE." as C on (pt.categoryID=C.categoryID and C.enabled=1) where  pt.picture!='' ";
        if (CONF_SHOW_PRODUCT_INSTOCK == 0) $sql .= ' and pt.in_stock>0';
        $sql .=' order by sort_order';
        $result=products_to_show($sql);
	$smarty->assign("special_offers",$result['result']);
   }

?>