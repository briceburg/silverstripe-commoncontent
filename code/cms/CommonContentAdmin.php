<?php

class CommonContentAdmin extends ModelAdmin
{

    private static $url_segment = 'manage-content';

    private static $menu_title = 'Common Content';

    private static $model_importers = array();

    public function getManagedModels()
    {
        $models = array();

        // merge additional models
        foreach (CommonContentUtil::getDecoratedClasses() as $className) {
            $singleton = singleton($className);

            if ($singleton->stat('common_content_modeladmin')) {
                $models[$className] = array(
                    'title' => ($singleton->stat('common_content_modeladmin_title')) ?  : $singleton->plural_name()
                );
            }
        }
        return array_merge($models, parent::getManagedModels());
    }

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);

        $gridField = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));

        // stripe Print/CSV export
        $gridField->getConfig()->removeComponentsByType('GridFieldPrintButton');
        $gridField->getConfig()->removeComponentsByType('GridFieldExportButton');

        if ($this->modelClass == 'CommonContentBlock') {
            $config = GridFieldConfig::create()->addComponent($multi = new GridFieldAddNewMultiClass())
                ->addComponent($sort = new GridFieldSortableHeader())
                ->addComponent(new GridFieldDataColumns())
                ->addComponent(new GridFieldDetailForm())
                ->addComponent(new GridFieldEditButton())
                ->addComponent(new GridFieldDeleteAction(false))
                ->addComponent($pagination = new GridFieldPaginator());

            /*
             $this->addComponent(new GridFieldAddNewMultiClass());
             $this->addComponent(new GridFieldToolbarHeader());
             $this->addComponent(new GridFieldTitleHeader());
             $this->addComponent(new GridFieldEditableColumns());
             $this->addComponent(new GridFieldEditButton());
             $this->addComponent(new GridFieldDeleteAction(false));
             $this->addComponent(new GridFieldDetailForm());
            */

            $sort->setThrowExceptionOnBadDataType(false);
            $pagination->setThrowExceptionOnBadDataType(false);

            // Multi-Class Add Button
            /////////////////////////
            $classes = array(
                'CommonContentBlock' => singleton('CommonContentBlock')->CommonContentLabel()
            );

            foreach (SS_ClassLoader::instance()->getManifest()->getDescendantsOf('CommonContentBlock') as $className) {
                $class = singleton($className);
                $classes[$className] = "{$class->CommonContentLabel()}";
            }

            $multi->setClasses($classes);

            $gridField->setConfig($config);
        }

        // add SortOrder
        if (singleton($this->modelClass)->hasField('SortOrder')) {
            $gridField->getConfig()->addComponent(new GridFieldOrderableRows('SortOrder'));
        }

        return $form;
    }
}
