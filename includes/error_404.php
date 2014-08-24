<?php
/*****************************************************************************
 *                                                                           *
 * Lego SP - legosp.net                                                      *
 * Copyright (c) 2012 Sergey Piekhota. All rights reserved.                  *
 *                                                                           *
 ****************************************************************************/

if (isset($_GET["error_404"])) {
	$smarty->assign("main_content_template", "error_404.tpl.html");
}
?>