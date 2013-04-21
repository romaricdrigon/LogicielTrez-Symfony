<?php
namespace Trez\LogicielTrezBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Trez\LogicielTrezBundle\Entity\DeclarationTva;
use Symfony\Component\Validator\Constraints\DateTime;
use Trez\LogicielTrezBundle\Entity\Config;

class LoadConfigData implements FixtureInterface
{
    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        $config = new Config();
        $config->setCle('currency');
        $config->setValeur('€');

        $manager->persist($config);
        $manager->flush();

        $config2 = new Config();
        $config2->setCle('currentBudget');
        $config2->setValeur('');

        $manager->persist($config2);
        $manager->flush();

        $date = new \DateTime('0-0-0');
        $date->setDate(0,2,0);

        $declarationTVA = new DeclarationTva();
        $declarationTVA->setCommentaire("Déclaration de tva poubelle pour les factures un peu douteuses");
        $declarationTVA->setDate($date);
        $declarationTVA->setSoldePrecedent(0);
        $declarationTVA->setSoldeFinal(0);

    }
}
