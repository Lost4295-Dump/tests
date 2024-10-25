<?php

namespace App;


use DateTime;
use Exception;

class ToDoList
{

    public User $user;

    private array $items;
    public DateTime $lastItemCreatedDate;

    public function __construct(User $user)
    {
        $this->items = [];
        if ($user->isValid()){
            $this->user = $user;
        } else {
            throw new Exception("User invalide");
        }
    }

    /**
     * @throws Exception
     */
    public function addItem(Item $item): void
    {
        if (count($this->items) > 10) {
            throw new Exception("Le nouveau item n'est plus que 10");
        }
        $now = new DateTime();
        if (count($this->items) > 0) {
            if ($this->lastItemCreatedDate->diff($now)->format("%i") < 30) {
                throw  new  Exception("Le dernier item a été créé il y a moins de 30 minutes");
            }
            foreach ($this->items as $checkedItem) {
                if ($checkedItem->name == $item->name) {
                    throw new Exception("Il y a deja un item avec ce nom");
                }
            }
        }
        $this->items[] = $item;
        $this->lastItemCreatedDate = $now;
    }


    public function isMailNecessary(): bool
    {
        if (count($this->getItems()) == 8) { // à l'ajout du 8è, on envoie le mail
            $emailSender = new EmailSenderService();
            $emailSender->send($this->user);
        }
        return count($this->getItems()) >= 8; // mais ça peut être bien de continuer à check si c'est quasi rempli
    }
    /**
     * @throws Exception
     */
    public function removeItemWithIndex(int $index): void
    {
        if (count($this->items) >= $index) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        } else {
            throw new Exception("L'item $index n'est pas dans la liste");
        }
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function save(int $index): void
    {
        $this->items[$index]->saved = true;
    }

    public function clean(): void
    {
        foreach ($this->items as $index=> $item) {
            if (!$item->saved) {
                unset($this->items[$index]);
            }
        }
        $this->items = array_values($this->items);
    }
}