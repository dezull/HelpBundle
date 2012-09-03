<?php

namespace Dezull\Bundle\HelpBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * HelpTopicRepository
 */
class HelpTopicRepository extends EntityRepository
{
    public function getMaxSequenceByCategory($categoryId)
    {
        return $this->getEntityManager()
            ->createQuery("SELECT MAX(t.sequence) FROM DezullHelpBundle:HelpTopic t
                           JOIN t.category c
                           WHERE c.id = ?1")
            ->setParameter(1, $categoryId)
            ->getSingleScalarResult();
    }

    public function findByCategoryOrderBySequence($categoryId)
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT t FROM DezullHelpBundle:HelpTopic t
                 JOIN t.category c
                 WHERE c.id = ?1
                 ORDER BY t.sequence ASC")
            ->setParameter(1, $categoryId)
            ->getResult();
    }
}
