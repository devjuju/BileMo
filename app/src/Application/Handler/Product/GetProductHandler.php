<?php

namespace App\Application\Handler\Product;

use App\Application\DTO\Product\ProductDetailDTO;
use App\Application\Query\Product\GetProductQuery;
use App\Repository\ProductRepository;

/**
 * Handler CQRS chargé de récupérer
 * le détail d'un produit.
 *
 * Il transforme l'entité Product en DTO
 * pour l'exposition via l'API.
 */
class GetProductHandler
{
    /**
     * Injection du repository Product.
     *
     * Permet d'accéder aux données en base
     * sans exposer Doctrine à la couche supérieure.
     */
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    /**
     * Traitement de la requête GetProductQuery.
     *
     * @param GetProductQuery $query
     * @return ProductDetailDTO|null
     */
    public function handle(GetProductQuery $query): ?ProductDetailDTO
    {
        // Récupération du produit en base
        $product = $this->productRepository->find($query->id);

        // Si aucun produit trouvé, on retourne null
        if (!$product) {
            return null;
        }

        /**
         * Transformation de l'entité en DTO.
         *
         * On évite de retourner directement l'entité Doctrine
         * pour garder une séparation claire des couches.
         */
        return new ProductDetailDTO(
            $product->getId(),
            $product->getName(),
            $product->getBrand(),
            $product->getDescription(),
            $product->getPrice(),
            $product->getStock(),
            $product->getColor(),
            $product->getStorage(),
            $product->getScreenSize()
        );
    }
}
