<?php

namespace App\Application\DTO\Product;

/**
 * DTO représentant le détail complet
 * d'un produit BileMo.
 *
 * Cette classe sert à transporter les données
 * entre la couche Application et l'API
 * sans exposer directement l'entité Product.
 */
class ProductDetailDTO
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

        // Nom commercial du téléphone
        public readonly string $name,

        // Marque du téléphone
        public readonly string $brand,

        // Description détaillée du produit
        public readonly string $description,

        // Prix du produit
        public readonly float $price,

        // Quantité disponible en stock
        public readonly int $stock,

        // Couleur du téléphone
        public readonly string $color,

        // Capacité de stockage du téléphone
        public readonly string $storage,

        // Taille de l'écran du téléphone
        public readonly string $screenSize
    ) {}
}
