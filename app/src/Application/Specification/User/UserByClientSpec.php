<?php

namespace App\Application\Specification\User;

use App\Application\Specification\SpecificationInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Specification permettant de restreindre
 * les utilisateurs à un client donné.
 *
 * C'est une règle de sécurité B2B essentielle
 * dans l'API BileMo.
 */
class UserByClientSpec implements SpecificationInterface
{
    /**
     * Client propriétaire des utilisateurs.
     */
    public function __construct(
        private $client
    ) {}

    /**
     * Application du filtre de sécurité
     * sur le QueryBuilder Doctrine.
     *
     * @param QueryBuilder $qb
     * @param string $alias Alias de l'entité (ex: 'u')
     */
    public function apply(QueryBuilder $qb, string $alias): void
    {
        $qb->andWhere("$alias.client = :client")
            ->setParameter('client', $this->client);
    }
}
