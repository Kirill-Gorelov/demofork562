<?php

use PHPUnit\Framework\TestCase;
use Backend\Modules\EnerIblocks\Engine\CElement;

class CElementTest extends TestCase
{
    private $CElement;
    protected function setUp()
    {
        $this->CElement = new CElement;
    }

    protected function tearDown()
    {
    }

    /**
     * We have one big test method, because setUp and tearDown are executed for every test method.
     */
    public function testClassMethods()
    {
        $rez = $this->CElement->getById(36);
        // assertNotEmpty($rez);
        // $this->assertTrue(1==1);
    }
}
