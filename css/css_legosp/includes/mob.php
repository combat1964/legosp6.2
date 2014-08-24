<?php
  if (isset($_GET['js']))
  {
      session_start();
      $by=0;
      if (isset($_SESSION['counts']) && count($_SESSION['counts'])>0)
        $by=array_sum($_SESSION['counts']);
      header("Content-type: application/x-javascript");  
      die('var by='.$by.';');
  }    
  if (isset($_GET['mobile']))
  {
    $smarty->assign("categories_tree",category_tree_list());
    $smarty->display("./css/css_" . CONF_COLOR_SCHEME . "/theme/category_tree_full.tpl.html");
    exit;
  }
?>