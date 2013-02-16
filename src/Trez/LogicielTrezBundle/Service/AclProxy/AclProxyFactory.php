<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy;

use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManager;
use Trez\LogicielTrezBundle\Service\AclProxy\AclBuilder\ExerciceAclBuilder;
use Trez\LogicielTrezBundle\Service\AclProxy\AclBuilder\BudgetAclBuilder;
use Trez\LogicielTrezBundle\Service\AclProxy\AclBuilder\CategorieAclBuilder;
use Trez\LogicielTrezBundle\Service\AclProxy\AclBuilder\SousCategorieAclBuilder;
use Trez\LogicielTrezBundle\Service\AclProxy\AclBuilder\LigneAclBuilder;

class AclProxyFactory
{
    protected $securityContext;
    protected $entityManager;

    /*
     * Service initialisation
     */
    public function __construct(SecurityContext $sc, EntityManager $em)
    {
        $this->securityContext = $sc;
        $this->entityManager = $em;
    }

    /*
     * SERVICE API
     * Used in all controllers!
     */

    /*
     * Get a proxied entity
     *
     * @param $type type of the entity we pass
     * @param entity
     *
     * @return entity who went through AclProxy filters
     */
    public function get($type, $entity)
    {
        return $this->getBuilder($type, $entity)->getProxy()->getProxied();
    }

    /*
     * The same, but for Exercices (1st level)
     */
    public function getAll()
    {
        $exercices = $this->entityManager->getRepository('TrezLogicielTrezBundle:Exercice')->findAll();

        $exercicesProxied = array();

        foreach ($exercices as $exercice) {
            $builder = $this->getBuilder('Exercice', $exercice);

            if ($builder->isValid()) {
                $exercicesProxied[] = $builder->getProxy()->getProxied();
            }
        }

        return $exercicesProxied;
    }

    /*
     * BUILDER API
     */

    public function getBuilder($type, $entity)
    {
        switch ($type) {
            case 'Exercice':
                $builder = new ExerciceAclBuilder($this, $entity);
                break;
            case 'Budget':
                $builder = new BudgetAclBuilder($this, $entity);
                break;
            case 'Categorie':
                $builder = new CategorieAclBuilder($this, $entity);
                break;
            case 'SousCategorie':
                $builder = new SousCategorieAclBuilder($this, $entity);
                break;
            case 'Ligne':
                $builder = new LigneAclBuilder($this, $entity);
                break;
            default:
                throw new \Exception('Unknown type '.$type);
        }

        $builder->build();

        return $builder;
    }

    public function getSecurityContext()
    {
        return $this->securityContext;
    }
}