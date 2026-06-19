<?php

namespace App\Application\Handler\User;

use App\Application\DTO\User\UserListDTO;
use App\Application\Query\User\GetUsersQuery;
use App\Application\Specification\AbstractSpecification;
use App\Application\Specification\User\UserByClientSpec;
use App\Application\Specification\User\UserByEmailSpec;
use App\Application\Specification\User\UserByNameSpec;
use App\Repository\UserRepository;

class GetUsersHandler
{
    public function __construct(
        private UserRepository $repository
    ) {}

    public function handle(GetUsersQuery $query): array
    {
        /**
         * =========================
         * QUERY DATA
         * =========================
         */
        $qb = $this->repository->createQueryBuilder('u');

        /**
         * =========================
         * QUERY COUNT (séparée)
         * =========================
         */
        $countQb = $this->repository->createQueryBuilder('u');

        /**
         * Specs
         */
        $specs = [];

        // sécurité client obligatoire
        $specs[] = new UserByClientSpec($query->client);

        if ($query->email) {
            $specs[] = new UserByEmailSpec($query->email);
        }

        if ($query->name) {
            $specs[] = new UserByNameSpec($query->name);
        }

        /**
         * Application des specs sur les 2 query builders
         */
        if (!empty($specs)) {
            $spec = AbstractSpecification::and(...$specs);

            $spec->apply($qb, 'u');
            $spec->apply($countQb, 'u');
        }

        /**
         * =========================
         * DATA PAGINATION
         * =========================
         */
        $users = $qb
            ->setMaxResults($query->limit)
            ->setFirstResult(($query->page - 1) * $query->limit)
            ->getQuery()
            ->getResult();

        /**
         * =========================
         * COUNT SAFE
         * =========================
         */
        $total = (int) $countQb
            ->select('COUNT(DISTINCT u.id)')
            ->getQuery()
            ->getSingleScalarResult();

        /**
         * =========================
         * DTO
         * =========================
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
            'total' => $total
        ];
    }
}
