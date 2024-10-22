<?php
declare(strict_types=1);

namespace App;

use PHPUnit\Framework\TestCase;

class BetterUserTest extends TestCase
{

    public function testIsValid()
    {
        $user = new User("john@example.com","John", "Doe", "15-05-2000");
        $this->assertTrue($user->isValid());
    }

    /**
     * @dataProvider invalidDataProvider
     * @throws \Exception
     */
    public function testIsInvalid($email, $name, $lastName, $birthdate){
        $user = new User($email, $name, $lastName, $birthdate);

        $this->assertFalse($user->isValid());
    }

    public function testIsInvalidThrowsException(){
            $email="john@example.com";
            $name= "John";
            $lastName= "Doe";
            $birthdate= "15-9655-2655";
        $user = new User($email, $name, $lastName, $birthdate);
        $this->expectException(\Exception::class);
        $this->assertFalse($user->isValid());
    }
    public function invalidDataProvider(): iterable{
        yield "nullName"=>[
            "email"=>"john@example.com",
            "name"=> null,
            "lastName"=> "John",
            "birthdate"=>"15-05-2000"
        ];
        yield "nullLastName"=>[
            "email"=>"john@example.com",
            "name"=> "John",
            "lastName"=> null,
            "birthdate"=> "15-05-2000"
        ];
        yield "nullBirthdate"=>[
            "email"=>"john@example.com",
            "name"=> "John",
            "lastName"=> "Doe",
            "birthdate"=> null,
        ];
        yield "nullEmail"=>[
            "email"=>null,
            "name"=> "John",
            "lastName"=> "Doe",
            "birthdate"=> "15-05-2000"
        ];
        yield "TooYoung"=>[
            "email"=>"john@example.com",
            "name"=> "John",
            "lastName"=> "Doe",
            "birthdate"=> "15-05-2020"
        ];
        yield "wrongEmailFormat1"=>[
            "email"=>"johnexample.com",
            "name"=> "John",
            "lastName"=> "Doe",
            "birthdate"=> "15-05-2000"
        ];
        yield "wrongEmailFormat2"=>[
            "email"=>"john@example",
            "name"=> "John",
            "lastName"=> "Doe",
            "birthdate"=> "15-05-2000"
        ];
    }
}
