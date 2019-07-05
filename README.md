# collectionPlusJson-php

version: 1.0.0

## Introduction
>**Collection+JSON** is a JSON-based read/write hypermedia-type designed to support management and querying of simple collections.
>
>The **Collection+JSON** hypermedia type is designed to support full read/write capability for simple lists (contacts, >tasks, blog entries, etc.). The standard application semantics supported by this media type include Create, Read, Update, >and Delete (CRUD) along w/ support for predefined queries including query templates (similar to HTML "GET" forms). Write >operations are defined using a template object supplied by the server as part of the response representation."
>
>*From Collection+JSON [site](http://amundsen.com/media-types/collection/format/)*

##Installation

0. Install composer.phar (if you don't already have it)
    - [*nix installation guide](https://getcomposer.org/doc/00-intro.md#installation-nix)
    - [Windows installation guide](https://getcomposer.org/doc/00-intro.md#installation-windows)

1. Edit your `composer.json` file to include the library
    ```json
       "require": {
              "other/libraries" : "..."
              "nuxnik/collection-plus-json": "*"
       }
    ```

2. Check you are good to go
    ```PHP
       require '../vendor/autoload.php';

       $test = new \CollectionPlusJson\Collection('http://api.test.io');
       echo json_encode($test->output());
    ```
    That code should output:
    ```JSON
       {"collection":
          {"version":"1.0.0",
           "href":"http:\/\/api.test.io"
           }
       }
    ```

## Example
```php

//Build the collection object
$colors = new Collection('http://api.colors.io/');

//Add a Link to the collection object
$colors->addLink(new Link($colors->getHref()->extend('rss'), 'feed', 'rss-feed', '', 'Subscribe to our RSS feed!'));

//Build an item
$color1 = new Item($colors->getHref()->extend('1'));
$color1->addData('id', '1', 'This is the color id.')
       ->addData('hexValue', '#9932CC', 'This is the color in hex format.');

// or add data with dynamic setter
$color1->sethumanValue('DarkOrchid', 'This is the color in human readable format.');

$color1->addLink(new Link('http://www.w3schools.com/tags/ref_colornames.asp', 'source'));
$color1->addLink(new Link('http://www.w3schools.com/tags/ref_color_tryit.asp?hex=9932CC', 'colorTest'));

//Build a second item
$color2 = new Item($colors->getHref()->extend('2'));
$color2->addData('id', '2', 'This is the color id.')
       ->addData('hexValue', '#FFFFF0', 'This is the color in hex format.');

// or add data with dynamic setter
$color2->sethumanValue('DarkOrchid', 'This is the color in human readable format.');

$color2->addLink(new Link('http://www.w3schools.com/tags/ref_colornames.asp', 'source'));
$color2->addLink(new Link('http://www.w3schools.com/tags/ref_color_tryit.asp?hex=FFFFF0', 'colorTest'));

//Add both items
$colors->addItems([$color1, $color2]);

//Build a collection query
$query = new Query($colors->getHref()->extend('search'), 'search');
$query->addData('search');
$colors->addQuery($query);

//Set the collection template
$template = new Template();
$template->addData('id', 'This is the color id.')
         ->addData('hexValue', 'This is the color in hex format.')
         ->addData('humanValue', 'This is the color in human readable format.')
         ->addData('colorTest', 'Link to test how your color looks with other colors.');

// or add data with dynamic setter
$template->setSource('Link to colors source');

$colors->setTemplate($template);

//Set an error
$error = new Error('error-test', 'ABC123', 'This is a test error. Server has encountered a problem and could not process your request, please try later.');
$colors->setError($error);

//Send response
$app->response->headers->set('Content-Type', 'application/vnd.collection+json');
echo json_encode($colors->output());

/*
Output would be:

{
    "collection": {
        "version": "1.0.1",
        "href": "http://api.colors.io/",
        "links": [
            {
                "href": "http://api.colors.io/rss",
                "rel": "feed",
                "prompt": "Subscribe to our RSS feed!",
                "name": "rss-feed",
            }
        ],
        "items": [
            {
                "href": "http://api.colors.io/1",
                "data": [
                    {
                        "name": "id",
                        "value": "1",
                        "prompt": "This is the color id."
                    },
                    {
                        "name": "hexValue",
                        "value": "#9932CC",
                        "prompt": "This is the color in hex format."
                    },
                    {
                        "name": "humanValue",
                        "value": "DarkOrchid",
                        "prompt": "This is the color in human readable format."
                    }
                ],
                "links": [
                    {
                        "href": "http://www.w3schools.com/tags/ref_colornames.asp",
                        "rel": "source",
                    },
                    {
                        "href": "http://www.w3schools.com/tags/ref_color_tryit.asp?hex=9932CC",
                        "rel": "colorTest",
                    }
                ]
            },
            {
                "href": "http://api.colors.io/2",
                "data": [
                    {
                        "name": "id",
                        "value": "2",
                        "prompt": "This is the color id."
                    },
                    {
                        "name": "hexValue",
                        "value": "#FFFFF0",
                        "prompt": "This is the color in hex format."
                    },
                    {
                        "name": "humanValue",
                        "value": "Ivory",
                        "prompt": "This is the color in human readable format."
                    }
                ],
                "links": [
                    {
                        "href": "http://www.w3schools.com/tags/ref_colornames.asp",
                        "rel": "source",
                    },
                    {
                        "href": "http://www.w3schools.com/tags/ref_color_tryit.asp?hex=FFFFF0",
                        "rel": "colorTest",
                    }
                ]
            }
        ],
        "queries": [
            {
                "href": "http://api.colors.io/search",
                "rel": "search",
                "data": [
                    {
                        "name": "search",
                        "value": null,
                        "prompt": ""
                    }
                ]
            }
        ],
        "template": [
            {
                "name": "id",
                "value": "",
                "prompt": "This is the color id."
            },
            {
                "name": "hexValue",
                "value": "",
                "prompt": "This is the color in hex format."
            },
            {
                "name": "humanValue",
                "value": "",
                "prompt": "This is the color in human readable format."
            },
            {
                "name": "source",
                "value": "",
                "prompt": "Link to colors source."
            },
            {
                "name": "colorTest",
                "value": "",
                "prompt": "Link to test how your color looks with other colors."
            }
        ],
        "error": {
            "title": "error-test",
            "code": "ABC-123",
            "message": "This is a test error. Server has encountered a problem and could not process your request, please try later."
        }
    }
}

```
# Consume a Collection+JSON object

This functionality enables a plain json string in Collection+JSON format to be consumed and translated into a Collection object for easy manipulation.

Example with transfer object:

```php
// init Collection object with json string to parse
$collection = new Collection(json_decode($collectionJson, true));

// get the first item
$item = $collection->getFirstItem();

// get a fake transfer object
$entity = new ExampleEntity();

// add the data with dynamic getters from item object
$entity->setFoo($item->getFoo());
$entity->setBar($item->getBar());

// save to example database
$repo->persist($entity);
$repo->flush();
```
Example with template object from POST/PUT request. See [Collection+JSON Documentation](http://amundsen.com/media-types/collection/examples/#ex-write) for details

```php
// init Template object with json string to parse
$template = new Template(json_decode($collectionJsonTemplate, true));

// get a fake transfer object
$entity = new ExampleEntity();

// add the data with dynamic getters from template object
$entity->setFoo($template->getFoo());
$entity->setBar($template->getBar());

// save to example database
$repo->persist($entity);
$repo->flush();
```

# Create a client to communicate with Collection+JSON server

## GET all data

```php
// init the http client and collection class
$href = 'http://api.colors.io';
$client = new GuzzleClient(['base_uri' => $href]);
$collection = new \CollectionPlusJson\Collection( $href, $client );

// get data from endpoint
$collection = $collection->get();

// iterate through the items
$items = $collection->getItems();
foreach( $items as $item) {
   echo $item->getId() . "\n";
}
```

If the JSON object is the same as the one above the following will be output:

```
1
2
```

## GET single item and update it

```php
// init the http client and collection class
$href = 'http://api.colors.io';
$client = new GuzzleClient(['base_uri' => $href]);
$collection = new \CollectionPlusJson\Collection( $href, $client );

// get data from endpoint
$collection = $collection->get('/color2');

// show the hex value
$item = $collection->getFirstItem();
echo $item->getHexValue() . "\n";

// update the color
$item->setHexValue('#FF0000');
$item->setHumanValue('red');

// import the item to the collection template
$collection->getTemplate()->importItem($item);

// put/save to server
$collection = $collection->put();

// show the hex value
$item = $collection->getFirstItem();
echo $item->getHexValue() . "\n";
```

If the JSON object is the same as the one above the following will be output:

```
#FFFFF0
#FF0000
```

## POST and DELETE single item

```php
// init the http client and collection class
$href = 'http://api.colors.io';
$client = new GuzzleClient(['base_uri' => $href]);
$collection = new \CollectionPlusJson\Collection( $href, $client );

// get data from endpoint
$collection = $collection->get();

// enter values into the template
$tpl = $collection->getTemplate();
$tpl->setHexValue('#00FF00');
$tpl->setHumanValue('green');

// post/save to server
$collection = $collection->post();

// show the hex value
$item = $collection->getFirstItem();
echo $item->getHexValue() . "\n";

// delete the item
$item->delete();
```

If the JSON object is the same as the one above the following will be output:

```
#00FF00
```

## Crawl / follow links

```php
// init the http client and collection class
$href = 'http://api.colors.io';
$client = new GuzzleClient(['base_uri' => $href]);
$collection = new \CollectionPlusJson\Collection( $href, $client );

// get data from endpoint
$item = $collection->get()->getFirstItem()
$collection = $item->follow();

echo  $collection->getFirstItem()->getHumanValue();
```

If the JSON object is the same as the one above the following will be output:

```
DarkOrchid
```
