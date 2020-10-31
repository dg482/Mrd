<?php

namespace Dg482\Mrd\Resource;

use Dg482\Mrd\Builder\Form\BaseForms;
use Illuminate\Support\Collection;

/**
 * Trait RelationTrait
 * @package Dg482\Mrd\Resource
 */
trait RelationTrait
{
    /**
     * @param  string  $relation
     * @return RelationResource
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function hasOne(string $relation): RelationResource
    {
        $model = $this->getAdapter()->getModel()->{$relation};

        $resource = (isset($this->relations[$relation]) && class_exists($this->relations[$relation])) ?
            new $this->relations[$relation] : new RelationResource();
        $resource->setRelation($relation);
        $resource->setContext($this->getContext());
        if ($model === null) {
            $model = $resource->getModel();
            $model = (new $model);
        }

        $resource->setModel(get_class($model));
        $resource->getAdapter()->setModel($model);

        return $resource;
    }

    /**
     * @param  string  $relation
     * @return RelationResource
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function hasMany(string $relation): RelationResource
    {
        $model = $this->getAdapter()->getModel();

        /** @var Collection $collection */
        $collection = $model->{$relation};
        $relationModel = $collection->get(0);

        $resource = app()->make($this->relations[$relation]);
        $resource->setRelation($relation);
        $resource->setContext($this->getContext());

        if (null === $relationModel) {
            $relationModel = app()->make($resource->model);
        }

        $resource->setModel(get_class($relationModel));
        $resource->getAdapter()->setModel($relationModel);
        $resource->setCollection($collection ? $collection : collect([]));
        $resource->getTable(true);

        return $resource;
    }
}
