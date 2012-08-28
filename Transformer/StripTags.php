<?php

namespace Dezull\Bundle\HelpBundle\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class StripTags implements DataTransformerInterface
{
    private $allowableTags;

    public function __construct($allowableTags)
    {
        $this->setAllowableTags($allowableTags);
    }

    public function setAllowableTags($allowableTags)
    {
        $this->allowableTags = '<' . \str_replace(',', '><', $allowableTags) . '>';
    }

    /**
     * {@inheritDoc}
     */
    public function transform($data)
    {
        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($data)
    {
        if ($this->allowableTags) {
            return \strip_tags($data, $this->allowableTags);
        }

        return \strip_tags($data);
    }
}
