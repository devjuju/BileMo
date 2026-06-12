<?php

namespace App\Application\Specification;

use Doctrine\ORM\QueryBuilder;

/**
 * Specification composite OR.
 *
 * Permet de combiner plusieurs specifications
 * avec une logique "OU".
 *
 * Au moins une condition doit être vraie.
 */
class OrSpecification extends AbstractSpecification
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
     * Applique une combinaison OR des specifications
     * sur le QueryBuilder Doctrine.
     *
     * @param QueryBuilder $qb
     * @param string $alias Alias de l'entité
     */
    public function apply(QueryBuilder $qb, string $alias): void
    {
        // Création d'une expression OR (ORX Doctrine)
        $expr = $qb->expr()->orX();

        foreach ($this->specs as $spec) {

            // Clone du QueryBuilder pour isoler chaque condition
            $subQb = clone $qb;

            // Reset des WHERE pour repartir d'une base propre
            $subQb->resetDQLPart('where');

            // Application de la specification sur le sous-query
            $spec->apply($subQb, $alias);

            // Récupération de la condition générée
            $wherePart = $subQb->getDQLPart('where');

            // Ajout à l'expression OR globale
            if ($wherePart) {
                $expr->add($wherePart);
            }
        }

        // Application finale de la condition OR
        $qb->andWhere($expr);
    }
}
