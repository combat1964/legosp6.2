<?php
/*****************************************************************************
*                                                                           *
* Lego SP - lego.shop-script.org                                            *
* Copyright (c) 2012 Sergey Piekhota. All rights reserved.                  *
*                                                                           *
****************************************************************************/


	//last order step - providing customer information

	if (isset($_POST["order_custinfo"])) //place order
	{
		$smarty->assign("main_content_template", "order_custinfo.tpl.html");
	}

?>