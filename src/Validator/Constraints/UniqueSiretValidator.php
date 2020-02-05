<?php

namespace App\Validator\Constraints;

use App\Service\BusinessService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class UniqueSiretValidatorValidator
 * @package App\Validator\Constraints
 */
class UniqueSiretValidator extends ConstraintValidator
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
        if (!$constraint instanceof UniqueSiret) {
            throw new UnexpectedTypeException($constraint, UniqueSiret::class);
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
                ->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}