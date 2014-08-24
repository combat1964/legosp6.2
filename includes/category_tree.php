<?php
/*****************************************************************************
 *                                                                           *
 * Lego Edition SP - legosp.net                                              *
 * Copyright (c) 2012 Sergey Piekhota. All rights reserved.                  * 
 *                                                                           *
 ****************************************************************************/
if (CONF_SHOW_MENU==1)
   $smarty->assign("categories_tree",category_tree_list());
else
   $smarty->assign("categories_tree",category_tree_list($categoryID));
?>