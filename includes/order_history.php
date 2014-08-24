<?php
/*****************************************************************************
 *                                                                           *
 * Lego SP - legosp.net                                                      *
 * Copyright (c) 2012 Sergey Piekhota. All rights reserved.                  *
 *                                                                           *
 ****************************************************************************/

if (isset($_GET["order_history"]) && isset($_SESSION["cust_id"]))
{
   
   if (isset($_GET["orderid"]))
   {
    
     $order_nambe=(int)$_GET["orderid"];
     $order_history=db_arAll("select name,Price, Quantity  from ".ORDERED_CARTS_TABLE.' as `order_car` left join '.ORDERS_TABLE.' as `order` on (`order`.orderID=`order_car`.orderID) where `order`.orderID='.$order_nambe.' and `order`.`custID`='.(int)$_SESSION["cust_id"]." and name not like '".ADMIN_DISCOUNT_STRING." %'");
     $order_discont=db_assoc("select name,Price from ".ORDERED_CARTS_TABLE." where orderID=".$order_nambe." and name like '".ADMIN_DISCOUNT_STRING." %'");
     $order_info=db_arAll('select * from `'.ORDERS_TABLE.'` where `orderID`='.$order_nambe.' and custID='.(int)$_SESSION["cust_id"]);
     $smarty->assign('order_info', $order_discont);
     $smarty->assign("order_discont", $order_discont);
     $smarty->assign("order_history", $order_history);
     $smarty->assign("meta_title", ORDERS_HISTORY.' >> Заказ №'.$order_nambe);
     $smarty->assign("main_content_template", "order_history.tpl.html");
   }
   else
   { 
     $order_history=db_arAll("select `order`.`orderID` orderID, `order`.`order_time` order_time, status, sum(Price*Quantity) summ  from ".ORDERS_TABLE." as `order` LEFT JOIN ".ORDERED_CARTS_TABLE." as `order_car` USING ( `orderID` ) where `order`.`custID`=".(int)$_SESSION["cust_id"]." and name not like '".ADMIN_DISCOUNT_STRING." %' group by `order`.`orderID`");
     foreach ($order_history as $key=>$value)
     {
       $d=db_r("select Price from ".ORDERED_CARTS_TABLE." where orderID=".$value['orderID']." and name like '".ADMIN_DISCOUNT_STRING." %'");
       $order_history[$key]["summ"]=show_price($value["summ"]-$d);
     } 
     $smarty->assign("history", $order_history);
     $smarty->assign("meta_title", ORDERS_HISTORY);  
     $smarty->assign("main_content_template", "order_history.tpl.html");
   }   
}
?>