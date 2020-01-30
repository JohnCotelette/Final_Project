<?php

namespace App\Validator\Constraints;

use App\Repository\BusinessRepository;
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
     * @var BusinessRepository
     */
    private $businessRepository;

    /**
     * UniqueSiretValidatorValidator constructor.
     * @param BusinessRepository $businessRepository
     */
    public function __construct(BusinessRepository $businessRepository)
    {
        $this->businessRepository = $businessRepository;
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

        $isTheDatabaseHasAlreadyThisBusiness = $this->businessRepository->findOneBy(["siretNumber" => $value]);

        if ($isTheDatabaseHasAlreadyThisBusiness != null) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}