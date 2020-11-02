<?php


namespace App\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueEmail
 * @package App\Validator
 * @Annotation
 */
class UniquePseudo extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'Le Pseudo "{{ value }}" existe déjà !!';

}