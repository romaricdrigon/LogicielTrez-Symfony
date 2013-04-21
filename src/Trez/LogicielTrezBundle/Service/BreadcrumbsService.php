<?php

namespace Trez\LogicielTrezBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;
use Trez\LogicielTrezBundle\Entity\Facture;
use Trez\LogicielTrezBundle\Entity\Ligne;
use Trez\LogicielTrezBundle\Entity\SousCategorie;
use Trez\LogicielTrezBundle\Entity\Categorie;
use Trez\LogicielTrezBundle\Entity\Budget;
use Trez\LogicielTrezBundle\Entity\Exercice;

class BreadcrumbsService
{
    protected $entityManager;
    protected $router;
    protected $whiteOctoberBreadcrumbs;
    protected $tempBreadcrumbs;

    /*
     * DIC
     */
    public function __construct(EntityManager $entity_manager, Router $router, Breadcrumbs $white_october_breadcrumbs)
    {
        $this->entityManager = $entity_manager;
        $this->router = $router;
        $this->whiteOctoberBreadcrumbs = $white_october_breadcrumbs;
    }

    /*
     * Public API
     * setBreadcrumbs will create a corresponding WhiteOctoberBreadcrumbs object
     * @param $entity, false for just the first step
     * @param $last_step, an optional text for the last step
     * @param $replace_last, the text will replace the last step (instead of being added at the very end)
     */
    public function setBreadcrumbs($entity = false, $last_step = false, $replace_last = false)
    {
        if ($entity === false) {
            $this->setParentExercices();
        } else {
            switch (get_class($entity)) {
                case 'Trez\LogicielTrezBundle\Entity\Facture':
                    $this->setFacture($entity);
                    break;
                case 'Trez\LogicielTrezBundle\Entity\Ligne':
                    $this->setLigne($entity);
                    break;
                case 'Trez\LogicielTrezBundle\Entity\SousCategorie':
                    $this->setSousCategorie($entity);
                    break;
                case 'Trez\LogicielTrezBundle\Entity\Categorie':
                    $this->setCategorie($entity);
                    break;
                case 'Trez\LogicielTrezBundle\Entity\Budget':
                    $this->setBudget($entity);
                    break;
                case 'Trez\LogicielTrezBundle\Entity\Exercice':
                    $this->setExercice($entity);
                    break;
                default:
                    throw new \Exception('Only hierarchical entities are allowed, not '.get_class($entity));
                    break;
            }
        }

        // recopy tempBreadcrumbs in reverse order
        $this->tempBreadcrumbs = array_reverse($this->tempBreadcrumbs);

        // add/replace optional last step
        if ($last_step !== false) {
            if ($replace_last === true) {
                array_pop($this->tempBreadcrumbs);
            }

            $this->tempBreadcrumbs[] = new BreadcrumbsItem($last_step);
        }

        // save it to WhiteOctoberBreadcrumbsBundle service
        $this->whiteOctoberBreadcrumbs->addObjectArray(
            $this->tempBreadcrumbs,
            'text',
            'url'
        );
    }

    /*
     * Builder methods
     */
    protected function setFacture(Facture $entity)
    {
        $this->tempBreadcrumbs[] = new BreadcrumbsItem(
            'Facture '.$entity->getObjet(),
            $this->router->generate('facture_detail', array('id' => $entity->getId(), 'ligne_id' => $entity->getLigne()->getId()))
        );

        $this->setLigne($entity->getLigne());
    }
    protected function setLigne(Ligne $entity)
    {
        $this->tempBreadcrumbs[] = new BreadcrumbsItem(
            'Factures de '.$entity->getNom(),
            $this->router->generate('facture_index', array('ligne_id' => $entity->getId()))
        );

        $this->setSousCategorie($entity->getSousCategorie());
    }
    protected function setSousCategorie(SousCategorie $entity)
    {
        $this->tempBreadcrumbs[] = new BreadcrumbsItem(
            'Lignes de '.$entity->getNom(),
            $this->router->generate('ligne_index', array('sous_categorie_id' => $entity->getId()))
        );

        $this->setCategorie($entity->getCategorie());
    }
    protected function setCategorie(Categorie $entity)
    {
        $this->tempBreadcrumbs[] = new BreadcrumbsItem(
            'Sous-catégories de '.$entity->getNom(),
            $this->router->generate('sous_categorie_index', array('categorie_id' => $entity->getId()))
        );

        $this->setBudget($entity->getBudget());
    }
    protected function setBudget(Budget $entity)
    {
        $this->tempBreadcrumbs[] = new BreadcrumbsItem(
            'Catégories de '.$entity->getNom(),
            $this->router->generate('categorie_index', array('budget_id' => $entity->getId()))
        );

        $this->setExercice($entity->getExercice());
    }
    protected function setExercice(Exercice $entity)
    {
        $this->tempBreadcrumbs[] = new BreadcrumbsItem(
            'Budgets de '.$entity->getEdition(),
            $this->router->generate('budget_index', array('exercice_id' => $entity->getId()))
        );

        $this->setParentExercices();
    }
    protected function setParentExercices()
    {
        $this->tempBreadcrumbs[] = new BreadcrumbsItem(
            'Exercices',
            $this->router->generate('exercice_index')
        );
    }
}

/*
 * Very small utility class
 */
class BreadcrumbsItem
{
    protected $text;
    protected $url;

    public function __construct($text, $url = '')
    {
        $this->text = $text;
        $this->url = $url;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getUrl()
    {
        return $this->url;
    }
}
