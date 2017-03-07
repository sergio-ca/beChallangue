<?php
// src/AppBundle/Entity/CsvFile.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class CsvFile {

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Por favor inserta tu currículum")
     * @Assert\File(mimeTypes={"text/csv", "text/plain"})
     */
    private $file;

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }
}