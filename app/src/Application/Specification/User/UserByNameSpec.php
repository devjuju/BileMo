<?php

namespace App\Application\Specification\User;

use App\Application\Specification\AbstractSpecification;
use Doctrine\ORM\QueryBuilder;

/**
 * Specification permettant de filtrer
 * les utilisateurs par prénom ou nom.
 *
 * Elle applique une recherche partielle (LIKE)
 * sur les deux champs.
 */
class UserByNameSpec extends AbstractSpecification
{
    /**
     * Nom ou fragment de nom recherché.
     */
    public function __construct(private string $name) {}

    /**
     * Application du filtre sur le QueryBuilder.
     *
     * @param QueryBuilder $qb
     * @param string $alias Alias de l'entité (ex: 'u')
     */
    public function apply(QueryBuilder $qb, string $alias): void
    {
        $qb
            // Recherche sur prénom OU nom
            ->andWhere("$alias.firstname LIKE :name OR $alias.lastname LIKE :name")
            ->setParameter('name', '%' . $this->name . '%');
    }
}
