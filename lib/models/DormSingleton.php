<?php
namespace Dorm\Models;

/**
 * Base Singleton class for extension by others.
 * http://stackoverflow.com/questions/203336/creating-the-singleton-design-pattern-in-php5
 *
 */

class DormSingleton
{
    private static $instances = array();

    //cannot construct
    protected function __construct() {}

    //cannot clone
    protected function __clone() {}

    public function __wakeup()
    {
        throw new Exception("Cannot unserialize Singleton.");
    }

    public static function getInstance()
    {
        // late-static-bound class name
        $cls = get_called_class();

        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static;
        }

        return self::$instances[$cls];
    }
}

?>