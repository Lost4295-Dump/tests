<?php
declare(strict_types=1);

namespace App\OutdatedTests;

use App\CalculatorFirst;
use PHPUnit\Framework\TestCase;

class CalculatorFirstTest extends TestCase
{
    private CalculatorFirst $calculator;

    public function setUp(): void
    {
        $this->calculator = new CalculatorFirst();
    }


    public function testAdd(): void
    {
        $this->assertEquals(4, $this->calculator->add(2, 2));
    }

    public function testSub(): void
    {
        $this->assertEquals(0, $this->calculator->sub(2, 2));
    }

    public function testDiv(): void
    {
        $this->assertEquals(1, $this->calculator->div(2, 2));
    }

    public function testMul(): void
    {
        $this->assertEquals(4, $this->calculator->mul(2, 2));
    }

    public function testAvg(): void
    {
        $this->assertEquals(15, $this->calculator->avg([10, 20]));
    }

    public function testDivReturnsErr(): void
    {
        $this->assertEquals(0, $this->calculator->div(2, 0));
    }
    public function testAvgReturnsErr(): void
    {
        $this->assertEquals(0, $this->calculator->avg([]));
    }
}
