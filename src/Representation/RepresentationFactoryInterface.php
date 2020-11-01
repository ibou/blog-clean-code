<?php


namespace App\Representation;


/**
 * Interface RepresentationFactoryInterface
 * @package App\Infrastructure\Representation
 */
interface RepresentationFactoryInterface
{
    /**
     * @param string $paginator
     */
    public function create(string $paginator): void;
}