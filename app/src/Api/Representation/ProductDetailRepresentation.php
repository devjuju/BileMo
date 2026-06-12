<?php

namespace App\Api\Representation;

use App\Application\DTO\Product\ProductDetailDTO;

/**
 * Représentation HATEOAS du détail d’un produit.
 *
 * Cette classe transforme un DTO en tableau JSON
 * enrichi avec des liens de navigation API.
 *
 * Objectifs :
 * - standardiser les réponses API
 * - séparer la présentation de la logique métier
 * - respecter le niveau 3 du modèle Richardson (HATEOAS)
 */
class ProductDetailRepresentation
{
    public function __construct(
        private ProductDetailDTO $product
    ) {}

    /**
     * Transforme le ProductDetailDTO en structure JSON API.
     *
     * Retourne :
     * - les données du produit
     * - les liens HATEOAS associés
     */
    public function toArray(): array
    {
        return [

            /*
             * Données métier du produit
             */
            'id' => $this->product->id,
            'name' => $this->product->name,
            'brand' => $this->product->brand,
            'description' => $this->product->description,
            'price' => $this->product->price,
            'stock' => $this->product->stock,
            'color' => $this->product->color,
            'storage' => $this->product->storage,
            'screenSize' => $this->product->screenSize,

            /*
             * Liens HATEOAS
             */
            '_links' => [

                // Ressource courante
                'self' => [
                    'href' => '/api/products/' . $this->product->id,
                    'method' => 'GET'
                ],

                // Retour vers la collection
                'collection' => [
                    'href' => '/api/products',
                    'method' => 'GET'
                ]
            ]
        ];
    }
}
