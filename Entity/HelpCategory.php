<?php

namespace Dezull\Bundle\HelpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dezull\Bundle\HelpBundle\Entity\HelpCategory
 *
 * @ORM\Table(name="help_category", uniqueConstraints={@ORM\UniqueConstraint(name="name_idx", columns={"name"})})
 * @ORM\Entity(repositoryClass="Dezull\Bundle\HelpBundle\Entity\HelpCategoryRepository")
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
     * @var int $sequence
     *
     * @ORM\Column(name="sequence", type="smallint", length=3)
     */
    private $sequence;

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

    /**
     * Set sequence
     *
     * @param int $sequence
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }

    /**
     * Get name
     *
     * @return int 
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}
