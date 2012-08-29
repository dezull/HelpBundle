<?php

namespace Dezull\Bundle\HelpBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dezull\Bundle\HelpBundle\Entity\HelpTopic
 *
 * @ORM\Table(name="help_topic")
 * @ORM\Entity()
 */
class HelpTopic
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
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=120)
     */
    private $title;

    /**
     * @var text $content
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var Dezull\Bundle\HelpBundle\Entity\HelpCategory $category
     *
     * @ORM\ManyToOne(targetEntity="HelpCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return text 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set category
     *
     * @param Dezull\Bundle\HelpBundle\Entity\HelpCategory $category
     */
    public function setCategory(\Dezull\Bundle\HelpBundle\Entity\HelpCategory $category)
    {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return Dezull\Bundle\HelpBundle\Entity\HelpCategory 
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}
