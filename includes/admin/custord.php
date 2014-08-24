<?php
/****************************************************************************
*                                                                           *
* LegoSP - lego.shop-script.org                                             *
* Copyright (c) 2012 Sergey Piekhota. All rights reserved.                  *
*                                                                           *
****************************************************************************/

$sub_departments = array
		(
			array("id"=>"new_orders", "name"=>ADMIN_NEW_ORDERS),
			array("id"=>"complite_orders", "name"=>ADMIN_COMPLITE_ORDERS),
			
			array("id"=>"users", "name"=>ADMIN_USERS)
		);
$resualt=deny_menedger($sub_departments,"custord");	
$sub_departments=$resualt[0]; 

if (count($sub_departments)>0)
{


	//define a new admin department
	$admin_dpt = array(
		"id" => "custord", //department ID
		"sort_order" => 20, //sort order (less `sort_order`s appear first)
		"name" => ADMIN_CUSTOMERS_AND_ORDERS, //department name
		"sub_departments" => $sub_departments
	);
	add_department($admin_dpt);

	//show department if it is being selected
	if ($dpt == "custord")
	{
		//set default sub department if required
		if (!isset($sub)) $sub = "new_orders";

		//assign admin main department template
		$smarty->assign("admin_main_content_template", $admin_dpt["id"].".tpl.html");
		//assign subdepts
		$smarty->assign("admin_sub_departments", $admin_dpt["sub_departments"]);		
		//include selected sub-department
		if (file_exists("./includes/admin/sub/".$admin_dpt["id"]."_$sub.php") && (!array_search($admin_dpt["id"]."_".$sub, $resualt[1]))) //sub-department file exists
			include("./includes/admin/sub/".$admin_dpt["id"]."_$sub.php");
		else //no sub department found
		  $smarty->assign("admin_main_content_template", "notfound.tpl.html");

	}

	//safemode
	$smarty->assign("safemode", 0);

	$admin_sub_menus['custord'] = $admin_dpt["sub_departments"];
	$smarty->assign("admin_sub_menus", $admin_sub_menus);
}
?>