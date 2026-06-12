<?php

namespace App\Application\Specification\User;

use App\Application\Specification\AbstractSpecification;
use Doctrine\ORM\QueryBuilder;

/**
 * Specification permettant de filtrer
 * les utilisateurs par email.
 *
 * Le filtre utilise un LIKE pour permettre
 * une recherche partielle.
 */
class UserByEmailSpec extends AbstractSpecification
{
    /**
     * Email ou fragment d'email recherché.
     */
    public function __construct(private string $email) {}

    /**
     * Application du filtre sur le QueryBuilder.
     *
     * @param QueryBuilder $qb
     * @param string $alias Alias de l'entité (ex: 'u')
     */
    public function apply(QueryBuilder $qb, string $alias): void
    {
        $qb
            // Recherche partielle sur l'email
            ->andWhere("$alias.email LIKE :email")
            ->setParameter('email', '%' . $this->email . '%');
    }
}
