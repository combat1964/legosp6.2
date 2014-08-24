<?php
/*****************************************************************************
 *                                                                           *
 * Lego SP - legosp.net                                                      *
 * Copyright (c) 2012 Sergey Piekhota. All rights reserved.                  *
 *                                                                           *
 ****************************************************************************/

if (isset($_GET["sitemap"]))    
    {

        $sitemap_categorys=All_Categories(0,0);
        $smarty->assign("sitemap_categorys", $sitemap_categorys);  
        $smarty->assign("main_content_template", "sitemap.tpl.html");
    }
?>