<?php

namespace CollectionPlusJson;

use GuzzleHttp\Client as GuzzleClient;

/**
 * Client class for interaction with collection+json based servers
 *
 * @author S. Fleming <nuxnik@int5.net>
 * @since Wed 03 Jul 2019 10:52:46 AM CEST
 */
abstract class AbstractClient implements ClientInterface
{
    /**
     * The guzzle client object
     *
     * @var GuzzleClient
     */
    protected $client;

    /**
     * The class constructor
     *
     * @return void
     */
    public function __construct(GuzzleClient $client = null)
    {
        // add the client
        if(is_object($client)) {
            $this->setClient($client);
        }
    }

    /**
     * Set the client object
     *
     * @return Client
     */
    public function setClient(GuzzleClient $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Post template object to href
     *
     * @return void
     */
    public function post(string $extension = null, GuzzleClient $client = null): self
    {
        return $this->dispatch("POST", $extension, $client);
    }

    /**
     * Put template object to href
     *
     * @return Collection
     */
    public function put(string $extension = null, GuzzleClient $client = null): Collection
    {
        return $this->dispatch("PUT", $extension, $client);
    }

    /**
     * Put template object to href
     *
     * @return Collection
     */
    public function get(string $extension = null): Collection
    {
        return $this->dispatch("GET", $extension);
    }

    /**
     * delete this item
     *
     * @return Collection
     */
    public function delete(): Collection
    {
        return $this->dispatch("DELETE", $extension);
    }

    /**
     * Follow item link and retrieve the collection object
     *
     * @return Collection
     */
    public function follow()
    {
        return $this->get();
    }

    /**
     * Get link object by rel string
     *
     * @return Link
     */
    public function getLinkByRel(string $rel): Link
    {
        $links = $this->getLinks();
        foreach ($links as $link) {
            if ($link->getRel() == $rel) {
                return $link;
            };
        }

        return new Link('', '');
    }

    protected function dispatch(string $method, string $extension = null, GuzzleClient $client = null): self
    {
        // get the client to use
        if(!is_object($client)){
            if(!is_object($this->client)){
                throw new \Exception("Cannot make request, client object is not set.");
            } else {
                $client = $this->client;
            }
        }

        // get the proper resource
        if (!is_null($extension)) {
            $resource = $this->getHref()->extend($extension)->getUrl();
        } else {

            // reassign
            $resource = $this->getHref()->getUrl();
        }
        $config = [
            'headers' => [
                'Content-Type' => 'application/vnd.collection+json',
            ]
        ];

        // set the message body
        switch ($method) {
            case 'POST':
            case 'PUT':
                $tpl = new \stdClass();
                $tpl->template = $this->getTemplate()->output();
                $config["body"] = json_encode($tpl);
                break;
            default:
                break;
        }
        $response = $client->request(
            $method,
            $resource,
            $config
        );
        $body = $response->getBody();

        // create new collection
        $collection = new Collection(json_decode($body, true), $client);

        return $collection;
    }
}
