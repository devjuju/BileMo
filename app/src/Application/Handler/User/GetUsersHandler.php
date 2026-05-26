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

        $qb = $this->repository->createQueryBuilder('u');

        $specs = [];

        // 🔒 sécurité obligatoire
        $specs[] = new UserByClientSpec($query->client);

        // 🔍 filtres dynamiques
        if ($query->email) {
            $specs[] = new UserByEmailSpec($query->email);
        }
        if ($query->name) {
            $specs[] = new UserByNameSpec($query->name);
        }

        // 🔗 combinaison
        $spec = AbstractSpecification::and(...$specs);
        $spec->apply($qb, 'u');

        // 📄 pagination
        $users = $qb
            ->setMaxResults($query->limit)
            ->setFirstResult(($query->page - 1) * $query->limit)
            ->getQuery()
            ->getResult();

        // 🔢 total (important pour API)
        $countQb = clone $qb;

        $total = $countQb
            ->select('COUNT(u)')
            ->getQuery()
            ->getSingleScalarResult();

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
