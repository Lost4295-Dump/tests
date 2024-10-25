<?php

namespace App;

class EmailSenderService
{
    public function send(User $user) :bool
    {
        echo("$user->lastname, Votre boite de todo est presque remplie !!");
        return true;
    }
}