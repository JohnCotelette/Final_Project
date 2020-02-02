<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueSiret extends Constraint
{
    /**
     * @var string
     */
    public $message = "Ce numéro de Siret est déjà enregistré (vous pouvez contacter les administrateurs du site si vous pensez qu'il s'agit d'une erreur)";
}