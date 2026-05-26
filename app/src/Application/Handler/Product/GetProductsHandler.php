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
        $qb = $this->productRepository->createQueryBuilder('p');

        $specs = [];

        if ($query->brand) {
            $specs[] = new ProductBrandSpec($query->brand);
        }

        $specs[] = new ProductPriceRangeSpec(
            $query->minPrice,
            $query->maxPrice
        );

        if (!empty($specs)) {
            $spec = AbstractSpecification::and(...$specs);
            $spec->apply($qb, 'p');
        }

        // 📄 pagination
        $products = $qb
            ->setMaxResults($query->limit)
            ->setFirstResult(($query->page - 1) * $query->limit)
            ->getQuery()
            ->getResult();

        // 🔢 total (clone obligatoire)
        $countQb = clone $qb;

        $total = $countQb
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();

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
