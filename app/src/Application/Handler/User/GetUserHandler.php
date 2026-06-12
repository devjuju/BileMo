<?php

namespace App\Application\Handler\User;

use App\Application\DTO\User\UserDetailDTO;
use App\Application\Query\User\GetUserQuery;
use App\Repository\UserRepository;

/**
 * Handler CQRS chargé de récupérer
 * le détail d'un utilisateur.
 *
 * Il vérifie également que l'utilisateur
 * appartient bien au client authentifié.
 */
final class GetUserHandler
{
    /**
     * Injection du repository User.
     */
    public function __construct(
        private UserRepository $repository
    ) {}

    /**
     * Traitement de la requête GetUserQuery.
     *
     * @param GetUserQuery $query
     * @return UserDetailDTO|null
     */
    public function handle(
        GetUserQuery $query
    ): ?UserDetailDTO {

        // Recherche de l'utilisateur avec contrôle du client
        $user = $this->repository->findOneBy([
            'id' => $query->id,
            'client' => $query->client
        ]);

        /**
         * Si aucun utilisateur n'est trouvé :
         * - soit il n'existe pas
         * - soit il n'appartient pas au client
         */
        if (!$user) {
            return null;
        }

        /**
         * Transformation de l'entité en DTO
         * pour exposition API sécurisée.
         */
        return new UserDetailDTO(
            $user->getId(),
            $user->getEmail(),
            $user->getFirstname(),
            $user->getLastname()
        );
    }
}
