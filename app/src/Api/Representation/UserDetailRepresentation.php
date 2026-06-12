<?php

namespace App\Api\Representation;

use App\Application\DTO\User\UserDetailDTO;

/**
 * Représentation HATEOAS du détail d’un utilisateur.
 *
 * Cette classe transforme un DTO utilisateur
 * en réponse JSON enrichie avec des liens API.
 *
 * Objectifs :
 * - standardiser les réponses API
 * - séparer la présentation de la logique métier
 * - faciliter la navigation REST
 * - respecter le niveau 3 du modèle Richardson
 */
class UserDetailRepresentation
{
    public function __construct(
        private UserDetailDTO $user
    ) {}

    /**
     * Transforme le UserDetailDTO
     * en structure JSON API.
     *
     * Retourne :
     * - les informations utilisateur
     * - les actions disponibles via HATEOAS
     */
    public function toArray(): array
    {
        return [

            /*
             * Données utilisateur
             */
            'id' => $this->user->id,
            'email' => $this->user->email,
            'firstname' => $this->user->firstname,
            'lastname' => $this->user->lastname,

            /*
             * Liens HATEOAS
             */
            '_links' => [

                // Détail de l’utilisateur courant
                'self' => [
                    'href' => '/api/users/' . $this->user->id,
                    'method' => 'GET'
                ],

                // Suppression de l’utilisateur
                'delete' => [
                    'href' => '/api/users/' . $this->user->id,
                    'method' => 'DELETE'
                ],

                // Retour vers la collection utilisateurs
                'collection' => [
                    'href' => '/api/users',
                    'method' => 'GET'
                ],

                // Création d’un nouvel utilisateur
                'create' => [
                    'href' => '/api/users',
                    'method' => 'POST'
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
