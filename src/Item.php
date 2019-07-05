<?php

namespace CollectionPlusJson;

use CollectionPlusJson\AbstractClient;
use CollectionPlusJson\Util\Href;
use GuzzleHttp\Client as GuzzleClient;

class Item extends AbstractClient
{
    /**
     * Add the data editor trait
     */
    use DataEditorTrait;

    /** @var Href */
    protected $href;

    /** @var  Link[] */
    protected $links = array();

    /**
     * @param string|Href $href
     */
    public function __construct( $href, GuzzleClient $client = null )
    {
        parent::__construct($client);

        if(!$href instanceof Href){
            $href = new Href($href);
        }
        $this->href = $href;
    }

    /**
     * @param string|Href $href
     * @return Item
     */
    public function setHref( Href $href )
    {
        if(!$href instanceof Href){
            $href = new Href($href);
        }
        $this->href = $href;
        return $this;
    }

    /**
     * @return Href
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @param Link $link
     * @return Item
     */
    public function addLink( Link $link )
    {
        $this->links[] = $link;
        return $this;
    }

    /**
     * @return Link[]
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @return \StdClass
     */
    public function output()
    {
        $properties = get_object_vars( $this );
        unset($properties['client']);
        $object = new \StdClass();
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
            $object->$name = $value;
        }
        return $object;
    }
}
