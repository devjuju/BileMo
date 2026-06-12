<?php

namespace App\Application\DTO\Product;

/**
 * DTO représentant les informations
 * essentielles d'un produit dans une liste.
 *
 * Cette classe est utilisée pour exposer
 * uniquement les données nécessaires
 * dans le endpoint de liste des produits.
 */
class ProductListDTO
{
    /**
     * Constructeur du DTO produit.
     *
     * Les propriétés readonly garantissent
     * l'immuabilité des données.
     */
    public function __construct(

        // Identifiant unique du produit
        public readonly int $id,

        // Nom du téléphone
        public readonly string $name,

        // Marque du téléphone
        public readonly string $brand,

        // Prix du produit
        public readonly float $price,
    ) {}
}
