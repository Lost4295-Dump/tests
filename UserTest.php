<?php
declare(strict_types=1);

namespace App;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function testIsValid()
    {
        $user = new User("john@example.com","John", "Doe", "15-05-2000");
        $this->assertTrue($user->isValid());
    }
    public function testIsNotValidNullName(){
        $user = new User("john@example.com",null, "Doe", "15-05-2000");
        $this->assertFalse($user->isValid());
    }
    public function testIsNotValidNullLastName(){
        $user = new User("john@example.com","john", null, "15-05-2000");
        $this->assertFalse($user->isValid());
    }

    public function testIsNotValidWrongDateFormat(){
        $user = new User("john@example.com","john", "Doe", "1584512052000");
        $this->expectException(\Exception::class);
        $this->assertFalse($user->isValid());
    }
    public function testIsNotValidTooYoung(){
        $user = new User("john@example.com","john", "Doe", "15-05-2020");
        $this->assertFalse($user->isValid());
    }
    public function testIsNotValidNotGoodEmail(){
        $user = new User("johnexample.com","john", "Doe", "15-05-2000");
        $this->assertFalse($user->isValid());
    }
    public function testIsNotValidNotGoodEmailTwice(){
        $user = new User("john@example","john", "Doe", "15-05-2000");
        $this->assertFalse($user->isValid());
    }
}
