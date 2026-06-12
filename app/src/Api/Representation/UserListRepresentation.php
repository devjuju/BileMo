<?php

namespace App\Api\Representation;

use App\Application\DTO\User\UserListDTO;

/**
 * Représentation HATEOAS d'un utilisateur.
 *
 * Cette classe transforme un DTO User en tableau JSON
 * exploitable par l'API.
 */
class UserListRepresentation
{
    /**
     * Injection du DTO utilisateur.
     *
     * Le DTO contient uniquement les données utiles
     * à exposer dans la réponse API.
     */
    public function __construct(
        private UserListDTO $user
    ) {}

    /**
     * Transforme les données utilisateur en tableau.
     *
     * Ce tableau sera ensuite converti en JSON
     * dans la réponse HTTP.
     */
    public function toArray(): array
    {
        return [

            // Identifiant unique de l'utilisateur
            'id' => $this->user->id,

            // Prénom de l'utilisateur
            'firstname' => $this->user->firstname,

            // Nom de l'utilisateur
            'lastname' => $this->user->lastname,

            /**
             * Liens HATEOAS disponibles pour cette ressource.
             *
             * Ces liens permettent au client API de découvrir
             * les actions possibles sur la ressource utilisateur.
             */
            '_links' => [

                // Consulter le détail de l'utilisateur
                'self' => [
                    'href' => '/api/users/' . $this->user->id,
                    'method' => 'GET'
                ],

                // Supprimer l'utilisateur
                'delete' => [
                    'href' => '/api/users/' . $this->user->id,
                    'method' => 'DELETE'
                ],

                // Retourner à la liste des utilisateurs
                'collection' => [
                    'href' => '/api/users',
                    'method' => 'GET'
                ],

                // Créer un nouvel utilisateur
                'create' => [
                    'href' => '/api/users',
                    'method' => 'POST'
                ]
            ]
        ];
    }
}
