<?php

namespace App\Application\Specification;

use Doctrine\ORM\QueryBuilder;

/**
 * Classe abstraite de base pour le Specification Pattern.
 *
 * Elle définit la structure commune de toutes les specifications
 * et fournit des méthodes de composition (AND / OR).
 */
abstract class AbstractSpecification implements SpecificationInterface
{
    /**
     * Chaque specification doit implémenter sa propre logique
     * d'application sur un QueryBuilder Doctrine.
     */
    abstract public function apply(QueryBuilder $qb, string $alias): void;

    /**
     * Composition logique AND entre plusieurs specifications.
     *
     * Permet de combiner plusieurs filtres de manière fluide.
     *
     * @param SpecificationInterface[] $specs
     */
    public static function and(SpecificationInterface ...$specs): AndSpecification
    {
        return new AndSpecification(...$specs);
    }

    /**
     * Composition logique OR entre plusieurs specifications.
     *
     * Permet d'élargir les conditions de recherche.
     *
     * @param SpecificationInterface[] $specs
     */
    public static function or(SpecificationInterface ...$specs): OrSpecification
    {
        return new OrSpecification(...$specs);
    }
}
