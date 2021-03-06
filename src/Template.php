<?php

namespace CollectionPlusJson;

use CollectionPlusJson\DataEditorTrait;

class Template
{
    /**
     * Add the data editor trait
     */
    use DataEditorTrait {
        addData as public traitAddData;
    }

    /**
     * The class constructor.
     *
     * @param array|null $data An optional template json string to parse
     */
    public function __construct($data = null)
    {
        if(
            !is_null($data) 
            && 
            is_array($data)
            &&
            isset($data['template']['data'])
        ){

            //assign the json data array
            foreach ($data['template']['data'] as $row) {
                $this->traitAddData($row['name'], $row['value']);
            }
        }
    }

    /**
     * Override the default method
     *
     * @param string $name The name of the data
     * @param string $prompt The prompt name
     * @param string $value The value. Note: this is ignored 
     */
    public function addData( $name, $prompt = '', $value = '' )
    {
        return $this->traitAddData($name, '', $prompt);
    }

    /**
     * @return array
     */
    public function output()
    {
        $objects = $this->data ? : array();
        foreach ($objects as &$val) {
            $val = $val->output();
        }
        $wrapper = new \stdClass();
        $wrapper->data = $objects;
        return $wrapper;

    }

    /**
     * Get the query json
     * @return string
     */
    public function getQuery()
    {
        $properties = array(
            'data' => $this,
        );
        $wrapper = new \stdClass();
        $collection = new \StdClass();
        foreach ($properties as $name => $value) {
            if (is_array( $value )) {
                foreach ($value as &$val) {
                    if (is_object( $val )) {
                        $val = $val->output();
                    }
                }
            }
            if (is_object( $value ) && !$value instanceof \StdClass) {
                $value = $value->output();
            }
            $collection->$name = $value;
        }
        $wrapper->template = $collection;
        return json_encode($wrapper);
    }

    /**
     * Import Item object into template
     *
     * @return Template
     */
    public function importItem(Item $item)
    {
        foreach ($this->data as $templateData) {
            foreach ($item->getData() as $itemData) {
                if ($itemData->getName() === $templateData->getName()) {
                    $templateData->setName($itemData->getName());
                    $templateData->setValue($itemData->getValue());
                    $templateData->setPrompt($itemData->getPrompt());
                }
            }
        }

        return $this;
    }
}
