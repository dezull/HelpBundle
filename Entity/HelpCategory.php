<?php

namespace Dezull\Bundle\HelpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dezull\Bundle\HelpBundle\Entity\HelpCategory
 *
 * @ORM\Table(name="help_category")
 * @ORM\Entity()
 */
class HelpCategory
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=120)
     */
    private $name;

    /**
     */
    private $locale;


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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}
