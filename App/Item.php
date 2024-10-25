<?php

namespace App;

class Item
{
    public string $name;
    public string $content;
    public \DateTime $createdDate;

    public  bool $saved;

    public function __construct(string $name, string $content){
        $this->name = $name;
        $this->content = $content;
        $this->createdDate = new \DateTime();
        $this->saved = false;
    }

    public function isValid(): bool
    {
        if (strlen($this->content) > 1000) {
            throw new \Exception("Le texte est trop long.");
        }
        if (strlen($this->content) < 1) {
            throw new \Exception("Le texte est trop court.");
        }
        if (strlen($this->name) < 1) {
            throw new \Exception("Il n'y a pas de nom renseigné.");
        }
        return true;
    }
}