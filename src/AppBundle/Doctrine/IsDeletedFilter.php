<?php

namespace AppBundle\Doctrine;

use Doctrine\ORM\Query\Filter\SQLFilter;
use Doctrine\ORM\Mapping\ClassMetadata;

class IsDeletedFilter extends SQLFilter
{
    /**
     * Gets the SQL query part to add to a query.
     * @param ClassMetadata $targetEntity
     * @param type $targetTableAlias
     * @return The constraint SQL if there is available, empty string otherwise.
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias) {
        return sprintf('%s.is_deleted = false', $targetTableAlias);
    }
}