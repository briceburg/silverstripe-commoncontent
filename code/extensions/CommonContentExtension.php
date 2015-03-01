<?php

class CommonContentExtension extends DataExtension
{

    private static $common_content_label = null; // ~ classname

    private static $common_content_modeladmin = true; // ? add to modeladmin

    private static $common_content_modeladmin_title = null; // ~ plural name


    // used to automatically generate objects during /dev/build -
    private static $required_records = array();

    private static $db = array(
        'Title' => 'Varchar',
        'Meta' => 'Varchar',
        'Readonly' => 'Boolean'
    );

    public function canDelete($member = null)
    {
        return ($this->owner->Readonly) ? false : parent::canDelete($member);
    }

    public function populateDefaults()
    {
        $this->owner->Title = preg_replace("/([a-z]+)([A-Z])/", "$1 $2", $this->owner->ClassName);
        return parent::populateDefaults();
    }

    public function validate(ValidationResult $result)
    {
        if (empty($this->owner->Title)) {
            $result->error("Title is required.");
        } elseif ($obj = DataObject::get($this->owner->class)->filter(
            array(
                'Title' => $this->owner->Title,
                'ID:not' => $this->owner->ID
            ))->first()) {
            $result->error("A {$this->CommonContentLabel()} is already titled {$this->owner->Title}.");
        }
    }

    public function updateCMSFields(FieldList $fields)
    {
        if ($this->owner->Readonly) {
            $fields->replaceField('Title', new ReadonlyField('Title'));
        }

        $fields->removeByName('Meta');
        $fields->removeByName('Readonly');
        return $fields;
    }

    // template
    ///////////
    public function CommonContentLabel()
    {
        return ($this->owner->stat('common_content_label')) ?  : preg_replace("/([a-z]+)([A-Z])/", "$1 $2",
            $this->owner->ClassName);
    }

    // utility
    //////////
    public function requireDefaultRecords()
    {
        foreach ($this->owner->stat('required_records') as $definition) {

            $obj = new $this->owner->class();
            foreach (array_intersect_key($definition, $obj->db()) as $property => $value) {
                $obj->$property = $value;
            }

            if (! DataObject::get($this->owner->class)->filter('Title', $obj->Title)->first()) {
                $obj->write();
                $prefix = ($obj->Readonly) ? 'Readonly' : 'Normal';
                DB::alteration_message("Created $prefix {$this->owner->class} named `{$obj->Title}`.",
                    "created");
            }
        }
        return parent::requireDefaultRecords();
    }
}