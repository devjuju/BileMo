<?php

namespace App\Api\Representation;

use App\Application\DTO\Product\ProductListDTO;

/**
 * Représentation HATEOAS d’un produit
 * dans la liste paginée des produits.
 *
 * Cette classe transforme un DTO en réponse JSON
 * standardisée avec des liens de navigation API.
 *
 * Objectifs :
 * - séparer la présentation de la logique métier
 * - standardiser les réponses API
 * - ajouter les liens HATEOAS
 * - respecter le niveau 3 du modèle Richardson
 */
class ProductListRepresentation
{
    public function __construct(
        private ProductListDTO $product
    ) {}

    /**
     * Transforme le ProductListDTO
     * en structure JSON exploitable par l’API.
     *
     * Retourne :
     * - les informations principales du produit
     * - les liens HATEOAS associés
     */
    public function toArray(): array
    {
        return [

            /*
             * Données principales du produit
             */
            'id' => $this->product->id,
            'name' => $this->product->name,
            'brand' => $this->product->brand,
            'price' => $this->product->price,

            /*
             * Liens HATEOAS
             */
            '_links' => [

                // Détail du produit courant
                'self' => [
                    'href' => '/api/products/' . $this->product->id,
                    'method' => 'GET'
                ],

                // Retour vers la collection produits
                'collection' => [
                    'href' => '/api/products',
                    'method' => 'GET'
                ],

                // Documentation API
                'documentation' => [
                    'href' => '/api/doc',
                    'method' => 'GET'
                ]
            ]
        ];
    }
}
