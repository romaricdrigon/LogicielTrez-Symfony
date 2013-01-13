<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SousCategorieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SousCategorieRepository extends EntityRepository
{
    public function getAll($categorie_id)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select(array('s', 'SUM(l.debit) AS debit', 'SUM(l.credit) AS credit'))
            ->from('TrezLogicielTrezBundle:sousCategorie', 's')
            ->leftJoin('s.lignes', 'l')
            ->innerJoin('s.categorie', 'c')
            ->where('c.id = ?1')
            ->groupBy('s.id')
            ->orderBy('s.cle', 'ASC')
            ->setParameters(array(1 => $categorie_id));

        return $qb->getQuery()->getResult();
    }

    public function getLastCle($categorie_id)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('s.cle')
            ->from('TrezLogicielTrezBundle:SousCategorie', 's')
            ->innerJoin('s.categorie', 'c')
            ->where('c.id = ?1')
            ->orderBy('s.cle', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->setParameters(array(1 => $categorie_id));

        $result = $qb->getQuery()->getResult();
        $result[]['cle'] = 0;

        return $result;
    }

    public function getAllowed($categorie_id, $user_id)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select(array('s', 'SUM(l.debit) AS debit', 'SUM(l.credit) AS credit'))
            ->from('TrezLogicielTrezBundle:SousCategorie', 's')
            ->leftJoin('s.categorie', 'c')
            ->leftJoin('s.lignes', 'l')
            ->leftJoin('l.users', 'u')
            ->where('c.id = ?1')
            ->andWhere('u.id = ?2')
            ->orderBy('s.cle', 'ASC')
            ->groupBy('s.id')
            ->setParameters(array(1 => $categorie_id, 2 => $user_id));

        return $qb->getQuery()->getResult();
    }
}
