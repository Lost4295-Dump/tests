<?php
declare(strict_types=1);

namespace App;

final class User
{
    public string $email;
    public string $name;
    public string $lastname;
    public string $birthdate;

    public function __construct(?string $email, ?string $name, ?string $lastname, ?string $birthdate)
    {
        $this->email = $email??"";
        $this->name = $name??"";
        $this->lastname = $lastname??"";
        $this->birthdate = $birthdate??"";
    }

    /**
     * @throws \Exception
     */
    public function isValid(): bool
    {
        $now = new \DateTime();
        $birthDate = new \DateTime($this->birthdate);
        $isOver13 = $now->diff($birthDate)->format('%y');
        return $this->email &&
            $this->name &&
             $this->lastname &&
            preg_match("/[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}/", $this->email) &&
            $isOver13>=13;

    }
}
