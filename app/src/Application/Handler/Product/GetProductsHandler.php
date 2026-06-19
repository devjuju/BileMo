<?php

namespace App\Application\Handler\Product;

use App\Application\DTO\Product\ProductListDTO;
use App\Application\Query\Product\GetProductsQuery;
use App\Application\Specification\AbstractSpecification;
use App\Application\Specification\Product\ProductBrandSpec;
use App\Application\Specification\Product\ProductPriceRangeSpec;
use App\Repository\ProductRepository;

class GetProductsHandler
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    public function handle(GetProductsQuery $query): array
    {
        /**
         * =========================
         * 1. QUERY DATA (pagination)
         * =========================
         */
        $qb = $this->productRepository->createQueryBuilder('p');

        /**
         * =========================
         * 2. QUERY COUNT (séparée)
         * =========================
         */
        $countQb = $this->productRepository->createQueryBuilder('p');

        /**
         * Specs dynamiques
         */
        $specs = [];

        if ($query->brand) {
            $specs[] = new ProductBrandSpec($query->brand);
        }

        $specs[] = new ProductPriceRangeSpec(
            $query->minPrice,
            $query->maxPrice
        );

        /**
         * Application des specs sur LES DEUX requêtes
         * (IMPORTANT pour cohérence data / count)
         */
        if (!empty($specs)) {
            $spec = AbstractSpecification::and(...$specs);

            $spec->apply($qb, 'p');
            $spec->apply($countQb, 'p');
        }

        /**
         * =========================
         * 3. PAGINATION DATA
         * =========================
         */
        $products = $qb
            ->setMaxResults($query->limit)
            ->setFirstResult(($query->page - 1) * $query->limit)
            ->getQuery()
            ->getResult();

        /**
         * =========================
         * 4. COUNT SAFE (IMPORTANT)
         * =========================
         */
        $total = (int) $countQb
            ->select('COUNT(DISTINCT p.id)')
            ->getQuery()
            ->getSingleScalarResult();

        /**
         * =========================
         * 5. DTO TRANSFORMATION
         * =========================
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
            'total' => $total
        ];
    }
}
