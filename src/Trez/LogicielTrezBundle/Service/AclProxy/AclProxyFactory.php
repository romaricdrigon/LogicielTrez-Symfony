<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy;

use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManager;
use Trez\LogicielTrezBundle\Service\AclProxy\FetchStrategy\EagerFetchStrategy;
use Trez\LogicielTrezBundle\Service\AclProxy\FetchStrategy\LazyFetchStrategy;
use Trez\LogicielTrezBundle\Service\AclProxy\FetchStrategy\LigneFetchStrategy;
use Trez\LogicielTrezBundle\Service\AclProxy\Builder\ExerciceBuilder;
use Trez\LogicielTrezBundle\Service\AclProxy\Builder\BudgetAclBuilder;
use Trez\LogicielTrezBundle\Service\AclProxy\Builder\CategorieBuilder;
use Trez\LogicielTrezBundle\Service\AclProxy\Builder\SousCategorieBuilder;
use Trez\LogicielTrezBundle\Service\AclProxy\Builder\LigneBuilder;

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
     * @param strategy :
     *      'lazy' to get the current object and its first descendants (default),
     *      'eager' to get the whole hierarchy
     *
     * @return entity who went through AclProxy filters
     */
    public function get($type, $entity, $mode = 'lazy')
    {
        // create the corresponding strategy
        if ($mode === 'eager') {
            $strategy = new EagerFetchStrategy($this->entityManager, $this->securityContext);
        } else { // we enforce lazy as a default else
            $strategy = new LazyFetchStrategy($this->entityManager, $this->securityContext);
        }

        // create builder
        switch ($type) {
            case 'Exercice':
                $builder = new ExerciceBuilder($strategy, $entity);
                break;
            case 'Budget':
                $builder = new BudgetAclBuilder($strategy, $entity);
                break;
            case 'Categorie':
                $builder = new CategorieBuilder($strategy, $entity);
                break;
            case 'SousCategorie':
                $strategy = new LigneFetchStrategy($this->entityManager, $this->securityContext); // lignes uses a different strategy
                $builder = new SousCategorieBuilder($strategy, $entity);
                break;
            case 'Ligne':
                $builder = new LigneBuilder($strategy, $entity); // strategy->findChildren() is not important here
                break;
            default:
                throw new \Exception('Unknown type '.$type);
        }

        $director = new AclDirector($builder);

        return $director->constructEntity()->getEntity();
    }

    /*
     * The same, but for Exercices (1st level)
     */
    public function getAll($mode = 'lazy')
    {
        $exercices = $this->entityManager->getRepository('TrezLogicielTrezBundle:Exercice')->findAll();
        $exercicesProxied = array();

        // create the corresponding strategy
        if ($mode === 'eager') {
            $strategy = new EagerFetchStrategy($this->entityManager, $this->securityContext);
        } else { // we enforce lazy as a default else
            $strategy = new LazyFetchStrategy($this->entityManager, $this->securityContext);
        }

        foreach ($exercices as $exercice) {
            $builder = new ExerciceBuilder($strategy, $exercice);
            $director = new AclDirector($builder);
            $built = $director->constructEntity(false)->getEntity();

            if ($built !== false) {
                $exercicesProxied[] = $built;
            }
        }

        return $exercicesProxied;
    }
}