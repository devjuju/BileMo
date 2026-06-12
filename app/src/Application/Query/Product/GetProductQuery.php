<?php

namespace App\Application\Query\Product;

/**
 * Query CQRS utilisée pour récupérer
 * le détail d'un produit.
 *
 * Elle transporte uniquement l'identifiant
 * nécessaire à la requête de lecture.
 */
class GetProductQuery
{
    /**
     * Constructeur de la Query.
     *
     * Les propriétés readonly garantissent
     * l'immuabilité des données.
     */
    public function __construct(
        // Identifiant du produit à récupérer
        public readonly int $id
    ) {}
}
