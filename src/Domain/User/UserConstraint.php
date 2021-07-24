<?php declare(strict_types=1);

namespace FlexPHP\Bundle\UserBundle\Domain\User;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserConstraint
{
    public function __construct(array $data)
    {
        $errors = [];

        foreach ($data as $key => $value) {
            $violations = $this->getValidator()->validate($value, $this->{$key}());

            if (count($violations)) {
                $errors[] = (string)$violations;
            }
        }

        return $errors;
    }

    private function getValidator(): ValidatorInterface
    {
        return Validation::createValidator();
    }

    private function id(): array
    {
        return [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Type([
                'type' => 'int',
            ]),
        ];
    }

    private function email(): array
    {
        return [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Type([
                'type' => 'string',
            ]),
            new Assert\Length([
                'min' => 6,
                'max' => 80,
            ]),
        ];
    }

    private function name(): array
    {
        return [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Type([
                'type' => 'string',
            ]),
            new Assert\Length([
                'max' => 80,
            ]),
        ];
    }

    private function password(): array
    {
        return [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Type([
                'type' => 'string',
            ]),
        ];
    }

    private function timezone(): array
    {
        return [
            new Assert\NotNull(),
            new Assert\NotBlank(),
            new Assert\Type([
                'type' => 'string',
            ]),
        ];
    }

    private function statusId(): array
    {
        return [
            new Assert\Type([
                'type' => 'string',
            ]),
            new Assert\Length([
                'max' => 2,
            ]),
        ];
    }

    private function lastLoginAt(): array
    {
        return [
            new Assert\DateTime(),
        ];
    }

    private function createdBy(): array
    {
        return [
            new Assert\Type([
                'type' => 'int',
            ]),
        ];
    }

    private function updatedBy(): array
    {
        return [
            new Assert\Type([
                'type' => 'int',
            ]),
        ];
    }
}
