<?php
/*****************************************************************************
 *                                                                           *
 * Lego Edition SP - lego.shop-script.org                                    *
 * Copyright (c) 2012 Sergey Piekhota. All rights reserved.                  * 
 *                                                                           *
 ****************************************************************************/

   $q = db_query("SELECT productID, ".PRODUCTS_TABLE.".name, Price, ".PRODUCTS_TABLE.".picture, items_sold, ".PRODUCTS_TABLE.".hurl, list_price FROM ".PRODUCTS_TABLE." RIGHT JOIN ".CATEGORIES_TABLE." USING (categoryID) WHERE productID=7 and ".PRODUCTS_TABLE.".enabled='1' AND ".CATEGORIES_TABLE.".enabled='1' and ".PRODUCTS_TABLE.".picture!='' ORDER BY items_sold DESC LIMIT 0 , ".CONF_MAX_HITS) or die (db_error());
   $result = Array();
   while ($row = db_fetch_row($q))
   {
     if (file_exists("./products_pictures/".$row[3]))
     { 
	$s = rtrim($row[3], '.jpg');
	$row[3] = $s."-H.jpg";
	if ($row[5] != "" && CONF_CHPU) {$row[5] = REDIRECT_PRODUCT."/".$row[5];} else {$row[5] = "index.php?productID=".$row[0];}
        $row[2]=$row[2]/CURRENCY_val;
        $row['list_price']=$row[6];
	$row[6] = show_price($row[2]);
	$result[] = $row;
     }
   }
   unset($row,$q);
   $smarty->assign("hits_to_show", $result);
?>
