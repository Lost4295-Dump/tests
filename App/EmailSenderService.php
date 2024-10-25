<?php

namespace App;

class EmailSenderService
{
    public function send(User $user) :bool
    {
        // echo("$user->lastname, Votre boite de tâches est presque remplie !!"); // envoie mail
        return true;
    }
}