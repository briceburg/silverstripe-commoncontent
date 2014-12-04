<?php

class CommonContentBlock extends DataObject
{

    private static $label = null;

    private static $template = null; // defaults to block classname


    // used to automatically generate blocks during /dev/build -
    private static $required_records = array();

    private static $db = array(
        'Title' => 'Varchar',
        'Heading' => 'Varchar',
        'Content' => 'HTMLText',
        'Meta' => 'Varchar',
        'Readonly' => 'Boolean'
    );

    public function canDelete($member = null)
    {
        return ($this->Readonly) ? false : parent::canDelete($member);
    }

    public function populateDefaults()
    {
        $this->Title = preg_replace("/([a-z]+)([A-Z])/", "$1 $2", $this->ClassName);
        return parent::populateDefaults();
    }

    public function validate()
    {
        $result = parent::validate();

        if (empty($this->Title)) {
            $result->error("Title is required.");
        } elseif ($block = DataObject::get($this->class)->filter(
            array(
                'Title' => $this->Title,
                'ID:not' => $this->ID
            ))->first()) {
            $result->error("A {$block->getLabel()} is already titled {$this->Title}.");
        }

        return $result;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        //$fields->replaceField('Heading', $field = new NullableField(new TextField("Heading","Heading",$this->Heading)));


        if ($this->Readonly) {
            $fields->replaceField('Title', new ReadonlyField('Title'));
        }

        $fields->removeByName('Meta');
        $fields->removeByName('Readonly');
        return $fields;
    }

    // template
    ///////////
    public function getLabel()
    {
        return ($this->stat('label')) ?  : preg_replace("/([a-z]+)([A-Z])/", "$1 $2", $this->ClassName);
    }

    // utility
    //////////
    public function forTemplate()
    {
        $custom_template = ($this->stat('template')) ?  : $this->ClassName;

        return $this->renderWith(
            array(
                $custom_template,
                'CommonContentBlock'
            ));
    }

    public function requireDefaultRecords()
    {
        foreach ($this->stat('required_records') as $definition) {

            $block = new $this->class();
            foreach (array_intersect_key($definition, $block->db()) as $property => $value) {
                $block->$property = $value;
            }

            if (! DataObject::get($this->class)->filter('Title', $block->Title)->first()) {
                $block->write();
                $prefix = ($block->Readonly) ? 'Readonly' : 'Normal';
                DB::alteration_message("Created $prefix {$this->class} named `{$block->Title}`.", "created");
            }
        }
        return parent::requireDefaultRecords();
    }
}