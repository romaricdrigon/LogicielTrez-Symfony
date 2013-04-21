<?php
namespace Trez\LogicielTrezBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
* @Annotation
*/
class NumeroUnique extends Constraint
{
    public function validatedBy()
    {
        return 'numero_unique_service';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
