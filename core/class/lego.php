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
    function &is_loaded($class = '')
    {
        static $_is_loaded = array();

        if ($class != '') {
            $_is_loaded[strtolower($class)] = $class;
        }

        return $_is_loaded;
    }
    function &load_class($class, $directory = 'libs', $prefix = 'lego_')
    {

        static $_classes = array();
        $path = dirname(__FILE__) . '/';
        // Does the class exist?  If so, we're done...
        if (isset($_classes[$class])) {
            return $_classes[$class];
        }

        $name = FALSE;

        if (file_exists($path . $directory . '/' . $class . '.php')) {
            $name = $prefix . $class;

            if (interface_exists($name) === FALSE) {
                require($path . $directory . '/' . $class . '.php');
            }
        }
        if ($name === FALSE) {
            exit('Unable to locate the specified class: ' . $class . '.php');
        }
        $this->is_loaded($class);
        $this->$class= new $name();
        $_classes[$class] = $class;
        #print_r($_classes);
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
}