<?php
/**
 * Created by PhpStorm.
 * User: mlecz
 * Date: 22.08.2016
 * Time: 14:07
 */

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class User extends EntityRepository
{

    public function findNameOrEmail($login)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('u.name')->from('Application\Entity\User', 'u')
            ->where('u.name = :params')
            ->orWhere('u.email = :params')
            ->setParameter('params', $login);

        $query = $qb->getQuery();

        return $query->getResult();
    }

}