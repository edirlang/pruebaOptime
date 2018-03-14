<?php
/**
 * Created by PhpStorm.
 * User: Edixon Hernndez
 * Date: 13/03/18
 * Time: 01:37 PM
 */

namespace AppBundle\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProductService
{
    const PRODUCTS_PER_LIST = 10;
    private $entityManager;

    function __construct(EntityManager $manager)
    {
        $this->entityManager = $manager;
    }

    public function filtersProducts($filters, $index){
        $query = $this->entityManager->getRepository('AppBundle:Product')->createQueryBuilder('p')
            ->join('p.category','c')
            ->where('c.active = :state')
            ->setParameters(['state' => true]);

        if($filters['name'] != ''){
            $query->andWhere('p.name like :name')
            ->setParameter('name', "%".$filters['name']."%");
        }

        if($filters['code'] != ''){
            $query->andWhere('p.code like :code')
                ->setParameter('code', "%".$filters['code']."%");
        }

        if($filters['category'] != ''){
            $query->andWhere('c.name like :category_name')
                ->setParameter('category_name', "%".$filters['category']."%");
        }

        return [
            'max_index' => $this->getMaxIndex($query),
            'products' => $this->paginationResultQuery($query, $index)
        ];
    }

    private function paginationResultQuery(QueryBuilder $query, $index){

        if($index==1){
            $query->setFirstResult(0);
            $query->setMaxResults(self::PRODUCTS_PER_LIST);
            $paginator = new Paginator($query,$fetchJoinCollection = true);
            $products = $paginator->getIterator();
        }else{
            $query->setFirstResult(($index-1) * self::PRODUCTS_PER_LIST);
            $query->setMaxResults(self::PRODUCTS_PER_LIST);
            $paginator = new Paginator($query,$fetchJoinCollection = true);
            $products = $paginator->getIterator();
        }

        return $products;
    }

    private function getMaxIndex(QueryBuilder $query){
        $results = count($query->getQuery()->getResult());

        $maxIndex = ($results%self::PRODUCTS_PER_LIST != 0)
            ? intval($results/self::PRODUCTS_PER_LIST) + 1
            : intval($results/self::PRODUCTS_PER_LIST);
        return $maxIndex;
    }
}