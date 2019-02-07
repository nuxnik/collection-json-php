<?php

use \CollectionPlusJson\Util\Href;

class HrefTest extends PHPUnit_Framework_TestCase
{

    /** @var  \CollectionPlusJson\Util\Href */
    protected $href;

    public function setUp()
    {
        $this->href = new Href( 'http://test.com/api/' );
    }

    /**
     * @param $url
     *
     * @dataProvider validUrls
     */
    public function testAcceptedUrls($url)
    {
        $href = new Href($url);
        $this->assertInstanceOf('\CollectionPlusJson\Util\Href', $href);
    }

    /**
     * @param $url
     *
     * @dataProvider invalidUrls
     *
     * @expectedException \CollectionPlusJson\Util\Href\Exception\InvalidUrl
     */
    public function testNonAcceptedUrls($url)
    {
        new Href($url, true);
    }

    /**
     * @param $ext
     *
     * @dataProvider validExtensions
     */
    public function testExtendingUrlWithValidExtension($ext)
    {
        $currentUrl = $this->href->getUrl();
        $href2 = $this->href->extend($ext);
        $extendedUrl = $href2->getUrl();
        $this->assertEquals( $currentUrl . $ext, $extendedUrl );
    }

    /**
     * @param $ext
     *
     * @expectedException \CollectionPlusJson\Util\Href\Exception\InvalidUrl
     *
     * @dataProvider invalidExtensions
     */
    public function testExtendingUrlWithInvalidExtension($ext)
    {
        $this->href->extend($ext, true);
    }

    public function testOutput()
    {
        $this->assertEquals( 'http://test.com/api/', $this->href->output() );
    }

    /**
     * @param string $template The url template
     * @param string $result The desired result
     * @param array $keyValue The replacement key value
     *
     * @dataProvider urlTemplates
     */
    public function testUrlReplacement($template, $result, $key, $value)
    {
        $href = new Href($template);
        $href->replace($key, $value);
        $this->assertEquals($result, $href->getUrl());
    }

    public function validUrls()
    {
        return array(
            array('http://www.w3schools.com/tags/ref_colornames.asp'),
            array('http://www.w3schools.com/tags/ref_color_tryit.asp?hex=9932CC'),
            array('http://api.estimate.local'),
            array('https://test.com/api/'),
            array('http://localhost:8080/test/1'),
            array('http://test.com/api/test/1'),
            array('http://test.com/api/1/test'),
            array('http://test.com/api/1/2'),
            array('http://test.com/api/1/2/test/'),
            array('test.com/api/1/2/test/'),
            array('/'),
            array('/test/1'),
            array('/1/2'),
            array('/1/test'),
        );
    }

    public function invalidUrls()
    {
        return array(
            array('http://api.estimate.local.'),
            array('http://api.estimate.local._'),
            array('htp://api.estimate.local'),
            array('http//api.estimate.local'),
            array('http:/api.estimate.local'),
            array('http:api.estimate.local'),
            array('http://api..estimate.local'),
            array('http://api.estimate.local//'),
            array('http://api.estimate.local/test//1/'),
            array('http://api.estimate.local/test/1//'),
            array('.'),
            array('//'),
            array('..'),
            array('./'),
            array('./estimate.local'),
        );
    }

    public function validExtensions()
    {
        return array(
            array('ext'),
            array('1'),
            array('ext/t'),
            array('ext/test'),
        );
    }

    public function urlTemplates()
    {
        return array(
            array(
                'http://test.com/api/{resource}/',
                'http://test.com/api/foo/',
                'resource',
                'foo'
            ),
            array(
                'http://test.com/api/foo/{teSt_TEST}/',
                'http://test.com/api/foo/bar/',
                'teSt_TEST',
                'bar'
            )
        );
    }

    public function invalidExtensions()
    {
        return array(
            array('/ext'), //Current url already ends in "/"
            array('.'),
            array('_'),
            array('\\'),
            array('/'),
        );
    }
}
