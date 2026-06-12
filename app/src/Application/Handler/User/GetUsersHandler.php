<?php

namespace App\Application\Handler\User;

use App\Application\DTO\User\UserListDTO;
use App\Application\Query\User\GetUsersQuery;
use App\Application\Specification\AbstractSpecification;
use App\Application\Specification\User\UserByClientSpec;
use App\Application\Specification\User\UserByEmailSpec;
use App\Application\Specification\User\UserByNameSpec;
use App\Repository\UserRepository;

/**
 * Handler CQRS chargé de récupérer
 * la liste des utilisateurs d'un client.
 *
 * Il gère les filtres dynamiques, la sécurité
 * et la pagination.
 */
class GetUsersHandler
{
    /**
     * Injection du repository User.
     */
    public function __construct(
        private UserRepository $repository
    ) {}

    /**
     * Traitement de la requête GetUsersQuery.
     *
     * @return array{
     *   data: UserListDTO[],
     *   total: int
     * }
     */
    public function handle(GetUsersQuery $query): array
    {
        // Construction du QueryBuilder Doctrine
        $qb = $this->repository->createQueryBuilder('u');

        /**
         * Liste des specifications appliquées
         * dynamiquement selon les filtres.
         */
        $specs = [];

        // 🔒 Sécurité obligatoire : isolation par client
        $specs[] = new UserByClientSpec($query->client);

        // 🔍 Filtre par email
        if ($query->email) {
            $specs[] = new UserByEmailSpec($query->email);
        }

        // 🔍 Filtre par nom/prénom
        if ($query->name) {
            $specs[] = new UserByNameSpec($query->name);
        }

        /**
         * Combinaison des specifications
         * avec un AND logique.
         */
        $spec = AbstractSpecification::and(...$specs);
        $spec->apply($qb, 'u');

        /**
         * Pagination des résultats
         */
        $users = $qb
            ->setMaxResults($query->limit)
            ->setFirstResult(($query->page - 1) * $query->limit)
            ->getQuery()
            ->getResult();

        /**
         * Total des résultats (sans pagination)
         */
        $countQb = clone $qb;

        $total = $countQb
            ->select('COUNT(u)')
            ->getQuery()
            ->getSingleScalarResult();

        /**
         * Transformation en DTO
         */
        return [
            'data' => array_map(
                fn($user) => new UserListDTO(
                    $user->getId(),
                    $user->getEmail(),
                    $user->getFirstname(),
                    $user->getLastname()
                ),
                $users
            ),
            'total' => (int) $total
        ];
    }
}
