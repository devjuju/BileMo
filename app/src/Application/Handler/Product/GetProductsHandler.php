<?php

namespace App\Application\Handler\Product;

use App\Application\DTO\Product\ProductListDTO;
use App\Application\Query\Product\GetProductsQuery;
use App\Application\Specification\AbstractSpecification;
use App\Application\Specification\Product\ProductBrandSpec;
use App\Application\Specification\Product\ProductPriceRangeSpec;
use App\Repository\ProductRepository;

/**
 * Handler CQRS chargé de récupérer
 * la liste des produits avec filtres
 * et pagination.
 *
 * Il applique les specifications pour
 * construire dynamiquement la requête.
 */
class GetProductsHandler
{
    /**
     * Injection du repository Product.
     */
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    /**
     * Traitement de la requête GetProductsQuery.
     *
     * @return array{
     *   data: ProductListDTO[],
     *   total: int
     * }
     */
    public function handle(GetProductsQuery $query): array
    {
        // Construction de la requête Doctrine
        $qb = $this->productRepository->createQueryBuilder('p');

        /**
         * Liste des specifications appliquées
         * dynamiquement selon les filtres.
         */
        $specs = [];

        // Filtre par marque si fourni
        if ($query->brand) {
            $specs[] = new ProductBrandSpec($query->brand);
        }

        // Filtre par plage de prix
        $specs[] = new ProductPriceRangeSpec(
            $query->minPrice,
            $query->maxPrice
        );

        /**
         * Application des specifications combinées
         * avec un AND logique.
         */
        if (!empty($specs)) {
            $spec = AbstractSpecification::and(...$specs);
            $spec->apply($qb, 'p');
        }

        /**
         * Pagination des résultats
         */
        $products = $qb
            ->setMaxResults($query->limit)
            ->setFirstResult(($query->page - 1) * $query->limit)
            ->getQuery()
            ->getResult();

        /**
         * Calcul du total (requête clonée)
         *
         * Important pour ne pas impacter la requête principale.
         */
        $countQb = clone $qb;

        $total = $countQb
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();

        /**
         * Transformation en DTO
         */
        return [
            'data' => array_map(
                fn($product) => new ProductListDTO(
                    $product->getId(),
                    $product->getName(),
                    $product->getBrand(),
                    $product->getPrice()
                ),
                $products
            ),
            'total' => (int) $total
        ];
    }
}
