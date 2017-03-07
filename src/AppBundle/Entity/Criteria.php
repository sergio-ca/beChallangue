<?php

namespace AppBundle\Entity;

class Criteria {

    protected $type;
    protected $word;

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }
    public function getWord() {
        return $this->word;
    }

    public function setWord($word) {
        $this->word = $word;
    }
}