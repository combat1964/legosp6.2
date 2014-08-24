<?php
/**
 * Created by PhpStorm.
 * User: pehser
 * Date: 15.08.14
 * Time: 15:25
 */

class lego_products extends lego{
     function delete($productID)
     {
         if (is_array($productID))
             $width=' productID in ('.add_in($productID).')';
         else
             $width=' productID='.(int)$productID;
         // Удоляем фото товара и товар
         $pictures=db_arAll('select picture,big_picture,thumbnail,REPLACE(picture,\'.jpg\',\'-H.jpg\') hits, REPLACE(picture,\'.jpg\',\'-SC.jpg\') cart from '.PRODUCTS_TABLE.' where '.$width);
         $this->patch_dir=ROOT_DIR.'/products_pictures/';
         $this->delfiles($pictures);


         // Удоляем доп фото товара
         $pictures=db_arAll('select picture from '.THUMB_TABLE.' where '.$width);
         $this->patch_dir=ROOT_DIR.'/products_thumb/';
         $this->delfiles($pictures);
         db_query('DELETE FROM '.THUMB_TABLE.' where '.$width);

         // Удоляем фото доп параметров
         $pictures=db_arAll('select picture from '.PRODUCT_OPTIONS_V_TABLE.' where '.$width.' and picture !=\'\'');
         $this->patch_dir=ROOT_DIR.'/products_pictures/';
         $this->delfiles($pictures);
         db_query('DELETE FROM '.PRODUCT_OPTIONS_V_TABLE.' where '.$width);
         $q=db_query('DELETE FROM '.PRODUCTS_TABLE.' where '.$width);
         return db_num_rows($q);
     }
}

