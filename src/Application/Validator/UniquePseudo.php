<?php


namespace App\Application\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueEmail
 * @package App\Application\Validator
 * @Annotation
 */
class UniquePseudo extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'Le Pseudo "{{ value }}" existe déjà !!';

}