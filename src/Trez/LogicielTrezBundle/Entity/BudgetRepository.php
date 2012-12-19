<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BudgetRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BudgetRepository extends EntityRepository
{
    public function getAll($exercice_id)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select(array('b', 'SUM(l.debit) AS debit', 'SUM(l.credit) AS credit'))
            ->from('TrezLogicielTrezBundle:Budget', 'b')
            ->leftJoin('b.categories', 'c')
            ->leftJoin('c.sousCategories', 's')
            ->leftJoin('s.lignes', 'l')
            ->innerJoin('b.exercice', 'e')
            ->where('e.id = ?1')
            ->groupBy('b.id')
            ->setParameters(array(1 => $exercice_id));

        return $qb->getQuery()->getResult();
    }

    public function getAllowed($exercice_id, $user_id)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select(array('b', 'SUM(l.debit) AS debit', 'SUM(l.credit) AS credit'))
            ->from('TrezLogicielTrezBundle:Budget', 'b')
            ->leftJoin('b.categories', 'c')
            ->leftJoin('c.sousCategories', 's')
            ->leftJoin('s.lignes', 'l')
            ->innerJoin('b.exercice', 'e')
            ->innerJoin('c.users', 'u')
            ->where('e.id = ?1')
            ->andWhere('u.id = ?2')
            ->groupBy('b.id')
            ->setParameters(array(1 => $exercice_id, 2 => $user_id));

        return $qb->getQuery()->getResult();
    }
}
