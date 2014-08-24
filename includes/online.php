<?php
/*****************************************************************************
*                                                                           *
* Lego SP - lego.shop-script.org                                            *
* Copyright (c) 2012 Sergey Piekhota. All rights reserved.                  *
*                                                                           *
****************************************************************************/
if (CONF_ONLINE_ON)
{ 
	$managers_list = Array();

	$q = db_query("SELECT online_type, online_num, online_name FROM ".MANAGER_TABLE." WHERE online_type > 0 ORDER BY ID") or die (db_error());
	while ($row = db_fetch_row($q)) $managers_list[] = $row;

	$smarty->assign("manager_list", $managers_list);
} 
?>