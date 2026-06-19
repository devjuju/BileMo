<?php

namespace App\Application\Specification\Product;

use App\Application\Specification\AbstractSpecification;
use Doctrine\ORM\QueryBuilder;

/**
 * Specification permettant de filtrer
 * les produits par marque.
 *
 * Fait partie du Specification Pattern
 * utilisé pour construire des requêtes dynamiques.
 */
class ProductBrandSpec extends AbstractSpecification
{
    /**
     * Marque du produit à filtrer.
     */
    public function __construct(private string $brand) {}

    /**
     * Application du filtre sur le QueryBuilder.
     *
     * @param QueryBuilder $qb
     * @param string $alias Alias de l'entité (ex: 'p')
     */
    public function apply(QueryBuilder $qb, string $alias): void
    {
        $qb
            ->andWhere("$alias.brand = :brand")
            ->setParameter('brand', $this->brand);
    }
}
