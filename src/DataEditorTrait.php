<?php

namespace CollectionPlusJson;

/**
 * Implements magic methods to get and set by method name
 *
 */
trait DataEditorTrait
{
    /** @var  DataObject[] */
    protected $data = array();

    /**
     * @param $name
     * @throws \Exception
     * @return mixed
     */
    public function addData( $name, $value = null, $prompt = '')
    {
        try {
            $dataObject = new DataObject( $name, $value, $prompt );
            $this->data[] = $dataObject;
        } catch ( \Exception $e ) {
            throw new \Exception( 'Object could not be added: ' . $e->getMessage() );
        }
        return $this;
    }

    /**
     * @param bool $flatten flatten objects into an arry
     * @return DataObject[]
     */
    public function getData($flatten = false)
    {
        $data = [];
        if ($flatten) {
            foreach ($this->data as $d) {
                $data[$d->getName()] = $d->getValue();
            }
        } else {
            $data = $this->data;
        }
        return $data;
    }

    /**
     * Method not found error
     */
    protected function triggerNoMethodError($name)
    {
        trigger_error("Call to undefined method " . __CLASS__ . '::' . $name . '()', E_USER_ERROR);;
    }
    /**
     * Get a data object value by name
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if(preg_match('#^get(.+)#', $name, $match)){
            foreach ($this->data as $data) {
                if($data->getName() == lcfirst($match[1])){
                    return $data->getValue();
                }
            }
            $this->triggerNoMethodError($name);
        } else if(preg_match('#^set(.+)#', $name, $match)) {
            foreach ($this->data as $data) {
                if($data->getName() == lcfirst($match[1])){
                    $data->setValue($arguments[0]);
                    if (isset($arguments[1])) {
                        $data->setPrompt($arguments[1]);
                    }
                    return $this;
                }
            }
            $this->triggerNoMethodError($name);
        } else {
            $this->triggerNoMethodError($name);
        }
    }
}

