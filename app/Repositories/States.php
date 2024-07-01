<?php

/**
 *
 */
class States extends Repository
{
    static protected $entity = 'State';

    /**
     * 
     */
    public function findActive()
    {
        return $this->query(function($query) {
            $query->leftJoin('this.providers', 'p')
                ->having('COUNT(p.person) = 1')
                ->orderBy('this.id')
                ->groupBy('this.id');
        })->getResult();
    }

    /**
     * 
     */
    public function getActiveStatesAsArray(): array
    {
        return collect($this->findActive())
            ->map(function($state) {
                return $state->getId();
            })
            ->toArray();
    }
}
