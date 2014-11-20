<?php

namespace Lab\Component\TraceDump\Tests;

use Lab\Component\TraceDump\TraceDump;

class Test extends \PHPUnit_Framework_TestCase
{

    public function test()
    {
        include __DIR__."/../Resources/functions/lib/die_console.php";

        $a = 1;
        #$a = array(1);
        #$a = [1, 2];
        #$a = ["foo" => 123, "lorem ipsum" => 44432, "dave" => 9999];
        #$a = $this->getTEstArray();
        #$a = ["foo" => ["sun", "set", [666]]];
        #$a = ["mann" => new Mann()];
        #$a = ["mann"];
        $a = new Mann();

        #die(tde($a));
        die(TraceDump::tracedump($a));
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

