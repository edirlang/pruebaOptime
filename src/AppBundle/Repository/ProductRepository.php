<?php

namespace AppBundle\Repository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    private function getMainRepository(){
        return $this->getEntityManager()->getRepository('AppBundle:Product');
    }

    public function getActiveProducts(){
        $query = $this->getMainRepository()->createQueryBuilder("p")
            ->join('p.category', 'c')
            ->where('c.active = :state')
            ->setParameters(['state' => true]);

        return $query->getQuery()->getResult();
    }
}