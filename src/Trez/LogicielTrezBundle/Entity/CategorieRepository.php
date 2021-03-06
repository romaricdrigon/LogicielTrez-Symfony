<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CategorieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategorieRepository extends EntityRepository implements GettableRepositoryInterface
{
    public function getAll($budget_id)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select(array('c', 'SUM(l.debit) AS debit', 'SUM(l.credit) AS credit'))
            ->from('TrezLogicielTrezBundle:Categorie', 'c')
            ->leftJoin('c.sousCategories', 's')
            ->leftJoin('s.lignes', 'l')
            ->innerJoin('c.budget', 'b')
            ->where('b.id = ?1')
            ->groupBy('c.id')
            ->orderBy('c.cle', 'ASC')
            ->setParameters(array(1 => $budget_id));

        return $qb->getQuery()->getResult();
    }

    public function getLastCle($budget_id)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('c.cle')
            ->from('TrezLogicielTrezBundle:Categorie', 'c')
            ->innerJoin('c.budget', 'b')
            ->where('b.id = ?1')
            ->orderBy('c.cle', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->setParameters(array(1 => $budget_id));

        $result = $qb->getQuery()->getResult();
        $result[]['cle'] = 0;

        return $result;
    }

    public function getAllowed($budget_id, $user_id)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select(array('c', 'SUM(l.debit) AS debit', 'SUM(l.credit) AS credit'))
            ->from('TrezLogicielTrezBundle:Categorie', 'c')
            ->leftJoin('c.budget', 'b')
            ->leftJoin('c.sousCategories', 's')
            ->leftJoin('s.lignes', 'l')
            ->leftJoin('l.users', 'u')
            ->where('b.id = ?1')
            ->andWhere('u.id = ?2')
            ->groupBy('c.id')
            ->orderBy('c.cle', 'ASC')
            ->setParameters(array(1 => $budget_id, 2 => $user_id));

        return $qb->getQuery()->getResult();
    }
}
