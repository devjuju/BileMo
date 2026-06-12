<?php

namespace App\Application\Query\Product;

/**
 * Query CQRS utilisée pour récupérer
 * une liste de produits avec filtres
 * et pagination.
 *
 * Elle centralise les paramètres de recherche
 * envoyés par l'API.
 */
class GetProductsQuery
{
    /**
     * Constructeur de la Query.
     *
     * Les valeurs par défaut permettent
     * une utilisation simple de l'endpoint.
     */
    public function __construct(

        // Numéro de page (pagination)
        public readonly int $page = 1,

        // Nombre d'éléments par page
        public readonly int $limit = 5,

        // Filtre par marque (optionnel)
        public readonly ?string $brand = null,

        // Prix minimum (optionnel)
        public readonly ?float $minPrice = null,

        // Prix maximum (optionnel)
        public readonly ?float $maxPrice = null,

        // Filtre stock (optionnel)
        public readonly ?bool $inStock = null
    ) {}
}
