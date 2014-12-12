<?php

class CommonContentBlock extends DataObject
{
    private static $template = null; // defaults to classname

    private static $extensions = array(
      'CommonContentExtension'
    );

    // hides subclasses, we include in modeladmin via YML config
    private static $common_content_modeladmin = false;


    private static $db = array(
        'Heading' => 'Varchar',
        'Content' => 'HTMLText'
    );


    public function forTemplate()
    {
        $template = ($this->stat('template')) ?  : $this->ClassName;

        return $this->renderWith($template);
    }


}