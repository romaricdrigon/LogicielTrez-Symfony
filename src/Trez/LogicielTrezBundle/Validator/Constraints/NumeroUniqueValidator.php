<?php
namespace Trez\LogicielTrezBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

class NumeroUniqueValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }

    public function validate($objet, Constraint $constraint)
    {
        $factures = $this->entityManager->getRepository('TrezLogicielTrezBundle:Facture')->findBy(array(
            'numero' => $objet->getNumero(),
            'typeFacture' => $objet->getTypefacture()
        ));

        foreach ($factures as $facture) {
            if ($facture->getExercice() === $objet->getExercice() && $facture->getId() !== $objet->getId()) {
                $this->context->addViolationAt('numero', 'Ce numéro est déjà utilisé');
                break(1);
            }
        }
    }
}