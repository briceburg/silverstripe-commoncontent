<?php

/**
 * Utility functions for the FlexiForms module.
 */
class CommonContentUtil
{
    public static function get_module_dir()
    {
        return basename(dirname(dirname(__DIR__)));
    }

    /*
    public static function include_requirements()
    {
        $moduleDir = self::get_module_dir();
        Requirements::css($moduleDir . '/css/commoncontent.css');
    }
    */

    public static function getBlock(Array $filter, $className = null) {
        return self::getBlocks($filter, $className)->first();
    }

    public static function getBlocks(Array $filter, $className = null) {
        $className = ($className) ?: 'CommonContentBlock';
        return DataObject::get($className)->filter($filter)->sort('Readonly', 'DESC');
    }
}
