<?php
declare(strict_types=1);

namespace App\Tests;

use App\User;
use DateInterval;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;

class BetterUserTest extends TestCase
{

    public function testIsValid()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P25Y'));
        $user = new User("john@example.com","John", "Doe", $date, "This1sMyPassw0rd");
        $this->assertTrue($user->isValid());
    }

    /**
     * @dataProvider invalidDataProvider
     * @throws Exception
     */
    public function testIsInvalid($email, $name, $lastName, $birthdate, $password){
        $this->expectException(Exception::class);
        $user = new User($email, $name, $lastName, $birthdate, $password);
        //$this->assertFalse($user->isValid());
    }
    public function invalidDataProvider(): iterable{
        $date = new DateTime();
        $date->sub(new DateInterval('P18Y'));
        yield "nullName"=>[
            "email"=>"john@example.com",
            "name"=> null,
            "lastName"=> "John",
            "birthdate"=>$date,
            "password"=> "This1sMyPassw0rd"
        ];
        yield "nullLastName"=>[
            "email"=>"john@example.com",
            "name"=> "John",
            "lastName"=> null,
            "birthdate"=> $date,
            "password"=> "This1sMyPassw0rd"
        ];
        yield "nullBirthdate"=>[
            "email"=>"john@example.com",
            "name"=> "John",
            "lastName"=> "Doe",
            "birthdate"=> null,
            "password"=> "This1sMyPassw0rd"
        ];
        yield "nullEmail"=>[
            "email"=>null,
            "name"=> "John",
            "lastName"=> "Doe",
            "birthdate"=> $date,
            "password"=> "This1sMyPassw0rd"
        ];
        $dateY = new DateTime();
        $dateY->sub(new DateInterval("P6Y"));
        yield "TooYoung"=>[
            "email"=>"john@example.com",
            "name"=> "John",
            "lastName"=> "Doe",
            "birthdate"=> $dateY,
            "password"=> "This1sMyPassw0rd"
        ];
        yield "wrongEmailFormat1"=>[
            "email"=>"johnexample.com",
            "name"=> "John",
            "lastName"=> "Doe",
            "birthdate"=> $date,
            "password"=> "This1sMyPassw0rd"
        ];
        yield "wrongEmailFormat2"=>[
            "email"=>"john@example",
            "name"=> "John",
            "lastName"=> "Doe",
            "birthdate"=> $date,
            "password"=> "This1sMyPassw0rd"
        ];
    }
    public function testIsInvalidThrowsException(){
        $email="john@example.com";
        $name= "John";
        $lastName= "Doe";
        $this->expectException(Exception::class);
        $birthdate= new \DateTime("15-9655-2655");
        $password="This1sMyPassw0rd";
        $user = new User($email, $name, $lastName, $birthdate, $password);
        $this->assertFalse($user->isValid());
    }

    public function testPasswordIsConform()
    {
        $password="This1sMyPassw0rd";
        $this->assertTrue(User::isPasswordConform($password));
    }

    /**
     * @dataProvider notConformPasswords
     */
    public function testPasswordIsNotConform( string $password){
        $this->assertFalse(User::isPasswordConform($password));
    }


    public function notConformPasswords() : iterable
{
    yield "Too short"=> [
        "password"=> "Pass8",
    ];
    yield "Too long"=> [
        "password"=> "Passwordsosecureiterztsdhyfjguvkhibujgfydt(esr'zsfetdghyfjushouldpassimsosure1234567898774125845",
    ];
    yield "Missing Maj"=>
    [
        "password"=> "passwordsosec6re",
    ];
    yield "Missing min"=>[
        "password"=> "PASSWIRDSU654CURE",
    ];
    yield "empty string"=> [
        "password"=> "",
    ];
}
}
