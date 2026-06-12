<?php

namespace App\Application\Specification;

use Doctrine\ORM\QueryBuilder;

/**
 * Interface de base du Specification Pattern.
 *
 * Elle impose une méthode unique permettant
 * d'appliquer une règle métier sur une requête Doctrine.
 */
interface SpecificationInterface
{
    /**
     * Applique une spécification sur un QueryBuilder.
     *
     * @param QueryBuilder $qb  Requête Doctrine à modifier
     * @param string $alias     Alias de l'entité (ex: 'u', 'p')
     */
    public function apply(QueryBuilder $qb, string $alias): void;
}
