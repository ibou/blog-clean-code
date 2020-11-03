<?php


namespace App\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueEmail
 * @package App\Validator
 * @Annotation
 */
class UniqueEmail extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'L\'adresse email "{{ value }}" existe déjà /!\ ';

}