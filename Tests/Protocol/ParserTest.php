<?php

namespace Debril\RssAtomBundle\Protocol;

use Debril\RssAtomBundle\Protocol\Parser\AtomParser;
use Debril\RssAtomBundle\Protocol\Parser\FeedContent;
use Debril\RssAtomBundle\Protocol\Parser\Item;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-01-27 at 00:26:35.
 */
class ParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var AtomParser
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new AtomParser;
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser::resetTimezone
     */
    public function testResetTimezone()
    {
        Parser::resetTimezone();
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser::getSystemTimezone
     */
    public function testGetSystemTimezone()
    {
        Parser::resetTimezone();
        $tz = Parser::getSystemTimezone();

        $this->assertInstanceOf('\DateTimeZone', $tz);
        $this->assertEquals(date_default_timezone_get(), $tz->getName());
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser::convertToDateTime
     * @expectedException Debril\RssAtomBundle\Protocol\Parser\ParserException
     */
    public function testconvertToDateTimeException()
    {
        $string = '09-12-2012';
        Parser::convertToDateTime($string);
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser::convertToDateTime
     */
    public function testconvertToDateTime()
    {
        $string = '2003-12-13T18:30:02Z';
        $date = Parser::convertToDateTime($string, \DateTime::RFC3339);

        $this->assertInstanceOf('\DateTime', $date);

        $this->assertEquals("13/12/2003", $date->format("d/m/Y"));
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser::setFactory
     */
    public function testSetFactory()
    {
        $this->object->setFactory(new Parser\Factory);

        $this->assertInstanceOf('\Debril\RssAtomBundle\Protocol\Parser\Factory', $this->object->getFactory());
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser::addAcceptableItem
     */
    public function testAddAcceptableItem()
    {
        $feed = new FeedContent();
        $item = new Item();
        $item->setUpdated(\DateTime::createFromFormat('j-M-Y', '17-Feb-2012'));
        $ret = $this->object->addAcceptableItem($feed, $item, \DateTime::createFromFormat('j-M-Y', '16-Feb-2012'));

        $this->assertInstanceOf("Debril\RssAtomBundle\Protocol\Parser", $ret);
        $this->assertEquals(1, $feed->getItemsCount());
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser::addAcceptableItem
     */
    public function testAddUnacceptableItem()
    {
        $feed = new FeedContent();
        $item = new Item();
        $date = new \DateTime;
        $item->setUpdated($date);
        $ret = $this->object->addAcceptableItem($feed, $item, $date);

        $this->assertInstanceOf("Debril\RssAtomBundle\Protocol\Parser", $ret);
        $this->assertEquals(0, $feed->getItemsCount());
    }

    /**
     * @covers Debril\RssAtomBundle\Protocol\Parser::addAcceptableItem
     * @expectedException \Exception
     */
    public function testAddAcceptableItemException()
    {
        $feed = new FeedContent;
        $item = new Item();
        $this->object->addAcceptableItem($feed, $item, \DateTime::createFromFormat('j-M-Y', '16-Feb-2012'));
    }

}