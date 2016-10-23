<?php
/**
 * User: Paweł
 * Date: 01.07.2016
 * Time: 19:54
 */

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class MapLocalization extends EntityRepository
{

    public function getSearch($search, $local)
    {

        $search = explode(', ', $search);

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('m')
            ->from('Application\Entity\MapLocalization', 'm');
        if ($search[0]) {
            if (!$search[1])
                $qb->where('m.nazwa LIKE :params OR m.datasm LIKE :params OR m.databg LIKE :params OR m.tagi LIKE :params OR m.ocena LIKE :params OR m.adres LIKE :params')
                    ->setParameter('params', '%' . $search[0] . '%');
            else
                $qb->where('m.nazwa LIKE :params1')
                    ->andWhere('m.adres LIKE :params2')
                    ->setParameter('params1', '%' . $search[0] . '%')
                    ->setParameter('params2', '%' . $search[1] . '%');
            $qb->andWhere('m.town = :local')
                ->setParameter('local', $local)
                ->andWhere('m.isActive = 1');
        } else {
            $qb->where('m.town = :local')
                ->setParameter('local', $local)
                ->andWhere('m.isActive = 1');
        }

        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function getAll($offset = 0, $limit = 10, $paginator = true)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('ml')
            ->from('Application\Entity\MapLocalization', 'ml')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        $query = $qb->getQuery();

        if ($paginator)
            return new Paginator($query);
        else
            return new $query->getResult();
    }

}