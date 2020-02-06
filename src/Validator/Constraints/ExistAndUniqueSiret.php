<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class ExistAndUniqueSiret
 * @package App\Validator\Constraints
 */
class ExistAndUniqueSiret extends Constraint
{
    /**
     * @var string
     */
    public $alreadyExistInDatabaseMessage = "Ce numéro de Siret est déjà enregistré (vous pouvez contacter les administrateurs du site si vous pensez qu'il s'agit d'une erreur)";

    /**
     * @var string
     */
    public $dontExistMessage = "L'entreprise portant ce numéro de Siret n'existe pas";
}