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
    public static function getBlock(Array $filter, $className = null)
    {
        return self::getBlocks($filter, $className)->first();
    }

    public static function getBlocks(Array $filter, $className = null)
    {
        $className = ($className) ?  : 'CommonContentBlock';
        return DataObject::get($className)->filter($filter)->sort('Readonly', 'DESC');
    }

    public static function getDecoratedClasses()
    {
        // @todo more efficient manner for finding classes extended by _extension_ ?
        static $classes;

        if ($classes === null) {
            $classes = array();
            $manifest = SS_ClassLoader::instance()->getManifest();

            foreach ($manifest->getDescendantsOf('DataObject') as $className) {
                if (Object::has_extension($className, 'CommonContentExtension')) {
                    $classes[] = $className;
                }
            }
        }
        return $classes;
    }
}
