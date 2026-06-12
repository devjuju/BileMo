<?php

namespace App\Application\Specification;

use Doctrine\ORM\QueryBuilder;

/**
 * Specification composite AND.
 *
 * Permet de combiner plusieurs specifications
 * en appliquant toutes les conditions successivement.
 */
class AndSpecification extends AbstractSpecification
{
    /**
     * Liste des specifications à combiner.
     *
     * @var SpecificationInterface[]
     */
    private array $specs;

    /**
     * Constructeur.
     *
     * @param SpecificationInterface ...$specs
     */
    public function __construct(SpecificationInterface ...$specs)
    {
        $this->specs = $specs;
    }

    /**
     * Applique toutes les specifications
     * sur le QueryBuilder Doctrine.
     *
     * @param QueryBuilder $qb
     * @param string $alias Alias de l'entité (ex: 'u', 'p')
     */
    public function apply(QueryBuilder $qb, string $alias): void
    {
        foreach ($this->specs as $spec) {
            $spec->apply($qb, $alias);
        }
    }
}
