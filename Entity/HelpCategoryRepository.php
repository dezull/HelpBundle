<?php

namespace Dezull\Bundle\HelpBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * HelpCategoryRepository
 */
class HelpCategoryRepository extends EntityRepository
{
    public function getMaxSequence()
    {
        return $this->getEntityManager()
            ->createQuery("SELECT MAX(c.sequence) FROM DezullHelpBundle:HelpCategory c")
            ->getSingleScalarResult();
    }

    public function findAllOrderBySequence()
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT c FROM DezullHelpBundle:HelpCategory c
                 ORDER BY c.sequence ASC")
            ->getResult();
    }
}
