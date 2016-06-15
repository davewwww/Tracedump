<?php

namespace Dwo\Tracedump\Tests;

use Dwo\Tracedump\Tracedump;

class Test extends \PHPUnit_Framework_TestCase
{

    public function testObject()
    {
        $a = new Mann();

        $output = Tracedump::tracedump($a);

        self::assertContains('Dwo\Tracedump\Tests\Mann', $output);
        self::assertContains('getBart()', $output);
        self::assertContains('Dwo\Tracedump\Tests\Mensch', $output);
        self::assertContains('__construct()', $output);
        self::assertContains('$bart', $output);
        self::assertContains('"drecking"', $output);

        #die(Tracedump::tracedump($a));
    }

    public function testStdClass()
    {

        $a = new \stdClass();
        $a->foo = 'bar';

        $output = Tracedump::tracedump($a);

        self::assertContains('$foo', $output);

        #die(Tracedump::tracedump($a));
    }

    private function getTEstArray()
    {
        return array(
            "foo"         => 123,
            "lorem ipsum" => 44432,
            "isGod"       => false,
            "isAdmin"     => true,
            "money"       => null,
            "dave"        => array(
                "sun"   => "XXXX",
                "empty" => array(),
                "moon"  => array(
                    1,
                    2,
                    3,
                    4,
                    5
                ),
                "venus" => array(
                    "12"       => "FilternEntriesVoterTest",
                    "12998777" => "PHPUnit_Framework_TestCase",
                )
            )
        );
    }
}

interface NameInterface
{
    public function getName();
}

class Mensch implements NameInterface
{
    public $name;
    public $haare = array("schwarz", "kurz");
    public $auto = null;

    function __construct()
    {
        $this->auto = new Auto();
        $this->haare = array(
            "schwarz",
            "kurz",
            "drecking" => array(
                new Auto(),
            )
        );
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}

class Auto
{
    public $type = "audi";

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}

class Mann extends Mensch
{
    public $bart;

    public function getBart()
    {
        return $this->bart;
    }

    public function setBart(NameInterface $bart, $asd = null)
    {
        $this->bart = $bart;
    }
}

