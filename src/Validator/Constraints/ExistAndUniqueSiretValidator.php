<?php

namespace App\Validator\Constraints;

use App\Service\BusinessService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class UniqueAndExistSiretValidatorValidator
 * @package App\Validator\Constraints
 */
class ExistAndUniqueSiretValidator extends ConstraintValidator
{
    /**
     * @var BusinessService
     */
    private $businessService;

    public function __construct(BusinessService $businessService)
    {
        $this->businessService = $businessService;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ExistAndUniqueSiret) {
            throw new UnexpectedTypeException($constraint, ExistAndUniqueSiret::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $businessAlreadyExist = $this->businessService->isBusinessAlreadyExistInTheDatabase($value);

        if ($businessAlreadyExist === true) {
            $this->context
                ->buildViolation($constraint->alreadyExistInDatabaseMessage)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }

        $businessExist = $this->businessService->isBusinessExist($value);

        if ($businessExist === false) {
            $this->context
                ->buildViolation($constraint->dontExistMessage)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}