<?php

// be sure to also apply commoncontentextension

class SortableCommonContentExtension extends DataExtension {

    private static $db = array(
        'SortOrder' => 'Int'
    );

    public function updateCMSFields(FieldList $fields)
    {
        $fields->replaceField('SortOrder', new HiddenField('SortOrder'));
    }

    public function augmentSQL(SQLQuery &$query)
    {
        if (! (bool) $query->getOrderBy()) {
            $query->setOrderBy('SortOrder');
        }
    }

}