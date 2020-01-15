<?php

class File extends CFormModel
{
    public $xml;

    // Define rules for file uploads
    // In this example, we want images of size less than 1MB
    public function rules()
    {
        return array(
            array('XML', 'file', 'allowEmpty' => false, 'safe' => true, 'types' => 'xml')
        );
    }
}