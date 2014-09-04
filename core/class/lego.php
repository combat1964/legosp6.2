<?php
/**
 * Created by PhpStorm.
 * User: psergey
 * Date: 24.07.14
 * Time: 15:41
 */

class lego {
    public $admin=FALSE;
    public $patch_dir='';
    private $_attribute=FALSE;
    private $_new=false;
    private $_table='';




    function load_class($class,$directory='libs',$prefix = 'lego_')
    {


        $path = dirname(__FILE__) . '/';
        // Does the class exist?  If so, we're done...
        $name = FALSE;

        if (file_exists($path . $directory . '/' . $class . '.php')) {
            $name = $prefix . $class;
            if (interface_exists($name) === FALSE) {
                require($path . $directory . '/' . $class . '.php');
                $this->$class = new $name;
            }


        }
        if ($name === FALSE) {
            exit('Unable to locate the specified class: ' . $class . '.php');
        }

        return $this;
    }
    function delfiles($pictures)
    {
        foreach ($pictures as $pic)
        {
            foreach ($pic as $picname)
            {
                if ($picname && file_exists($this->patch_dir.$picname))
                  unlink($this->patch_dir.$picname);
            }
        }
    }

    public function upload_img($file)
    {
       require_once dirname(__FILE__) . '/libs/class.upload.php';
        $handle = new upload($file);
        var_dump($handle->uploaded);
       if ($handle->uploaded) {
           $handle->file_new_name_body   = 'image_resized';
           $handle->image_resize         = true;
           $handle->image_x              = 100;
           $handle->image_ratio_y        = true;
           $handle->process(ROOT_DIR.'/products_pictures/');
           if ($handle->processed) {
               echo 'image resized';
               $handle->clean();
           } else {
               echo 'error : ' . $handle->error;
           }

       }
    }

    public function getIsNewRecord()
    {
        return $this->_new;
    }
    public function setIsNewRecord($value)
    {
        $this->_new=$value;
    }
    public function getattribute()
    {
        return $this->_attribute;
    }
    public function setattribute($attribute)
    {
        $this->_attribute=$attribute;
    }

    public function save()
    {
        return $this->getIsNewRecord() ? add_field($this->tableName(),$this->_attribute['data']) : update_field($this->tableName(),$this->_attribute['data'],$this->_attribute['where']);
    }
}

