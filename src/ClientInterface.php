<?php

namespace CollectionPlusJson;

use CollectionPlusJson\Util\Href;

/**
 * The client interface class 
 *
 * @package default
 * @author Me
 */
interface ClientInterface
{
    /**
     * This is required for following internal links
     *
     * @return Href
     */
    public function getHref();
}
