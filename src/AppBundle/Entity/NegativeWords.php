<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NegativeWords
 */
class NegativeWords
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $word;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set word
     *
     * @param string $word
     * @return NegativeWords
     */
    public function setWord($word)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get word
     *
     * @return string 
     */
    public function getWord()
    {
        return $this->word;
    }
}
