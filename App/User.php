<?php
declare(strict_types=1);

namespace App;

use Exception;

class User
{
    public string $email;
    public string $name;
    public string $lastname;
    public \DateTime $birthdate;
    public string $password;
    public ?ToDoList $toDoList;

    /**
     * @throws \DateMalformedStringException
     */
    public function __construct(?string $email, ?string $name, ?string $lastname, ?\DateTime $birthdate, ?string $password)
    {
        $this->email = $email??"";
        $this->name = $name??"";
        $this->lastname = $lastname??"";
        $this->birthdate = $birthdate?? new \DateTime();
        $this->password = $password??"";
        $this->toDoList = new ToDoList($this);
    }

    public function isValid(): bool
    {
        $now = new \DateTime();
        $isOver13 = $now->diff($this->birthdate)->format('%y');
        return $this->email &&
            $this->name &&
             $this->lastname &&
            self::isPasswordConform($this->password)&&
            preg_match("/[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}/", $this->email) &&
            $isOver13>=13;
    }
    public static function isPasswordConform($password): bool
    {
        return $password &&
            preg_match("/[A-Z]/", $password)&&
            preg_match("/[a-z]/", $password)&&
            preg_match("/[0-9]/", $password)&&
            8<=strlen($password) && strlen($password)<=40;

    }

}
