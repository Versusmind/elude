<?php namespace App\Libraries\Criterias\Interfaces;

use App\Libraries\Criterias\Criteria;
use Illuminate\Database\Eloquent\Builder;

/**
 * Interface CriteriaInterface
 *
 * @author  LAHAXE Arnaud
 * @package App\Libraries\Criterias
 */
interface CriteriaInterface {

    /**
     * @author LAHAXE Arnaud
     *
     *
     * @return array
     */
    public function getCriterias();

    /**
     * @author LAHAXE Arnaud
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return mixed
     */
    public function applyCriterias(Builder $query);

    /**
     * @author LAHAXE Arnaud
     *
     * @param \App\Libraries\Criterias\Criteria $criteria
     *
     * @return $this
     */
    public function addCriteria(Criteria $criteria);

    /**
     * @author LAHAXE Arnaud
     *
     * @param array $criterias
     *
     * @return $this
     */
    public function setCriterias(array $criterias);

    /**
     * @author LAHAXE Arnaud
     *
     * @return $this
     */
    public function clearCriterias();
}
