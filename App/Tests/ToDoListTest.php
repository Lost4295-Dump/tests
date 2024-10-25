<?php

namespace App\Tests;

use App\EmailSenderService;
use App\Item;
use App\ToDoList;
use App\User;
use Exception;
use PHPUnit\Framework\TestCase;

class ToDoListTest extends TestCase
{
    private ToDoList $toDoList;
    private User $user;
    public function setUp(): void
    {
        $date = new \DateTime();
        $date->sub(new \DateInterval('P15Y'));
        $this->user = new User("john@example.com","John", "Doe", $date, "Password1");
        $this->toDoList = new ToDoList($this->user);
    }

    /**
     * @throws Exception
     */


    public function testAddOneItemAndGetItemsValid()
    {
        $item= new Item("Nom", "Contenu");
        $this->toDoList->addItem($item);
        $this->assertSame([$item], $this->toDoList->getItems());
    }

    /**
     * @throws Exception
     */
    public function testAddItemsValid()
    {
        $item= new Item("Nom", "Contenu");
        $this->toDoList->addItem($item);

        $this->toDoList->lastItemCreatedDate->sub(new \DateInterval("P0DT31M"));
        $item2= new Item(" Nouveau Nom", "Contenu");

        $this->toDoList->addItem($item2);
        $this->assertEquals([$item, $item2], $this->toDoList->getItems());
    }

    public function testisMailNecessaryTrue(){
        for ($i =0; $i <8 ; $i++) {
            $item= new Item("Nom $i", "Contenu");
            $this->toDoList->addItem($item);
            $this->toDoList->lastItemCreatedDate->sub(new \DateInterval("P0DT30M"));
        }
        $this->toDoList->user= $this->user;
        $this->assertTrue($this->toDoList->isMailNecessary());
    }
    public function testisMailNecessaryFalse(){
        for ($i =0; $i <5 ; $i++) {
            $item= new Item("Nom $i", "Contenu");
            $this->toDoList->addItem($item);
            $this->toDoList->lastItemCreatedDate->sub(new \DateInterval("P0DT30M"));
        }
        $this->toDoList->user= $this->user;
        $this->assertFalse($this->toDoList->isMailNecessary());
    }
    /**
     * @throws Exception
     */
    public function testAddItemsValidAndSendEmail()
    {
        $email = $this->createMock(EmailSenderService::class);
        $email->expects($this->once())->method("send")->withAnyParameters()->will($this->returnValue(true));
        $this->toDoList->user= $this->user;
        $items = [];
        for ($i =0; $i < 9 ; $i++) {
            $item= new Item("Nom $i", "Contenu");
            $this->toDoList->addItem($item);
            $this->toDoList->lastItemCreatedDate->sub(new \DateInterval("P0DT30M"));
            $items[] = $item;
            if (count($this->toDoList->getItems())==8){
                $email->send($this->user);
            }
        }
        $this->assertEquals($items, $this->toDoList->getItems());
    }


    public function testAddItemInvalidTime()
    {
        $item= new Item("Nom", "Contenu");
        $this->toDoList->addItem($item);
        $this->toDoList->lastItemCreatedDate->sub(new \DateInterval("P0DT10M"));
        $item2= new Item(" Nouveau Nom", "Contenu");
        $this->expectException(Exception::class);
        $this->toDoList->addItem($item2);
    }

    public function testGetItemsOnEmpty()
    {
        $this->assertSame([], $this->toDoList->getItems());
    }

    /**
     * @throws Exception
     */
    public function testRemoveItemWithIndex()
    {
        $items = [];
        for ($i =0; $i <5 ; $i++) {
            $item= new Item("Nom $i", "Contenu");
            $this->toDoList->addItem($item);
            $this->toDoList->lastItemCreatedDate->sub(new \DateInterval("P0DT30M"));
            $items[] = $item;
        }
        $this->toDoList->removeItemWithIndex(3);
        unset($items[3]);
        $items= array_values($items);
        $this->assertEquals($items,$this->toDoList->getItems());

    }

    /**
     * @dataProvider removeItemInvalid
     */
    public function testRemoveItemWithIndexInvalid($index)
    {
        for ($i =0; $i <5 ; $i++) {
            $item= new Item("Nom $i", "Contenu");
            $this->toDoList->addItem($item);
            $this->toDoList->lastItemCreatedDate->sub(new \DateInterval("P0DT30M"));
        }
        $this->expectException(Exception::class);
        $this->toDoList->removeItemWithIndex($index);
    }



    public function testSave()
    {
        $save =$this->createMock(ToDoList::class);
        $save->expects($this->once())->method("save")->willThrowException(new Exception());
        for ($i =0; $i <8 ; $i++) {
            $item= new Item("Nom $i", "Contenu");
            $this->toDoList->addItem($item);
            $this->toDoList->lastItemCreatedDate->sub(new \DateInterval("P0DT30M"));
        }
        $this->expectException(Exception::class);
        $save->save(2);
    }

    public function testClear(){
        $items = [];
        for ($i =0; $i <8 ; $i++) {
            $item= new Item("Nom $i", "Contenu");
            if ($i%3==0){
               $item->saved= true;
            }
            $this->toDoList->addItem($item);
            $this->toDoList->lastItemCreatedDate->sub(new \DateInterval("P0DT30M"));
            $items[]= $item;
        }
        foreach ($items as $index => $item){
            if (!$item->saved){
                unset($items[$index]);
            }
        }
        $items = array_values($items);
        $this->toDoList->clean();
        $this->assertEquals($items, $this->toDoList->getItems());
    }

    public function testWhileUserIsInvalid(){
        $date = new \DateTime();
        $date->sub(new \DateInterval("P50Y"));
        $this->expectException(Exception::class);
        $this->user = new User(null, "John", "Doe",  $date, "Password1");
    }
    public function removeItemInvalid() : iterable
    {
        yield "oob"=> [
            "index"=>7895655656
        ];
        yield "superior"=>[
          "index"=>6
        ];

    }

}
