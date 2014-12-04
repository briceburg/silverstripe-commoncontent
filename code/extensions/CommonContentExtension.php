<?php

// @TODO maybe make a short static utility for getBlocks?

class CommonContentControllerExtension extends Extension {

    public function ContentBlockByTitle($Title) {
        return $this->ContentBlocksByTitle($Title)->first();
    }

    public function ContentBlockByType($ClassName){
        return $this->ContentBlocksByType($ClassName)->first();
    }

    public function ContentBlockByTitleAndType($Title, $ClassName) {
        return CommonContentUtil::getBlocks(array('Title' => $Title), $ClassName);
    }

    public function ContentBlocksByTitle($Title) {
        return CommonContentUtil::getBlocks(array('Title' => $Title));
    }

    public function ContentBlocksByType($ClassName){
        return CommonContentUtil::getBlocks(array(),$ClassName);
    }

}