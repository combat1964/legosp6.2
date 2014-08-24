<?php
if(!defined('WORKING_THROUGH_ADMIN_SCRIPT'))
{
	die;
}


if (!strcmp($sub, "order_statuses"))
{





 $ord_status=db_arAll('select * from '.ORDER_STATUS_TABLE. ' order by sort_order');
 $smarty->assign("ord_status", $ord_status);
 $smarty->assign("admin_sub_dpt", "order_statuses.tpl.html");
  
}
?>