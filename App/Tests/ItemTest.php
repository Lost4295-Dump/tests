<?php

namespace App\Tests;

use App\Item;
use Exception;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    private Item $item;

    public function setUp(): void
    {
        $this->item = new Item("Nom", "contenu");
    }

    /**
     * @throws Exception
     */
    public function testIsValid()
    {
        $this->assertTrue($this->item->isValid());
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testIsInvalidContentTooLong()
    {
        $this->item->content=random_bytes(1001);
        $this->expectException(Exception::class);
        $this->item->isValid();
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testIsInvalidContentTooShort()
    {
        $this->item->content="";
        $this->expectException(Exception::class);
        $this->item->isValid();
    }
    /**
     * @return void
     * @throws Exception
     */
    public function testIsInvalidName()
    {
        $this->item->name="";
        $this->expectException(Exception::class);
        $this->item->isValid();
    }


}
