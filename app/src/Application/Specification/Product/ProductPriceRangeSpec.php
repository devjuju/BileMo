<?php

namespace App\Application\Specification\Product;

use App\Application\Specification\AbstractSpecification;
use Doctrine\ORM\QueryBuilder;

/**
 * Specification permettant de filtrer
 * les produits selon une plage de prix.
 *
 * Elle applique des conditions dynamiques
 * sur le prix minimum et/ou maximum.
 */
class ProductPriceRangeSpec extends AbstractSpecification
{
    /**
     * Bornes de prix (optionnelles).
     */
    public function __construct(
        private ?float $min,
        private ?float $max
    ) {}

    /**
     * Application des filtres de prix
     * sur le QueryBuilder Doctrine.
     *
     * @param QueryBuilder $qb
     * @param string $alias Alias de l'entité (ex: 'p')
     */
    public function apply(QueryBuilder $qb, string $alias): void
    {
        /**
         * Filtre prix minimum
         */
        if ($this->min !== null) {
            $qb->andWhere("$alias.price >= :min")
                ->setParameter('min', $this->min);
        }

        /**
         * Filtre prix maximum
         */
        if ($this->max !== null) {
            $qb->andWhere("$alias.price <= :max")
                ->setParameter('max', $this->max);
        }
    }
}
