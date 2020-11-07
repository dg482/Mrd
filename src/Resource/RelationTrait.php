<?php

namespace Dg482\Mrd\Resource;

/**
 * Trait RelationTrait
 * @package Dg482\Mrd\Resource
 */
trait RelationTrait
{
    /**
     * @param  string  $relation
     * @return RelationResource
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
     */
    public function hasMany(string $relation): RelationResource
    {
        $model = $this->getAdapter()->getModel();

        /** @var array $collection */
        $collection = $model->{$relation};
        $relationModel = current($collection);

        $resource = (new $this->relations[$relation]);
        $resource->setRelation($relation);
        $resource->setContext($this->getContext());

        if (null === $relationModel) {
            $relationModel = (new $resource->model);
        }

        $resource->setModel(get_class($relationModel));
        $resource->getAdapter()->setModel($relationModel);
        $resource->setCollection($collection ? $collection : []);
        $resource->getTable(true);

        return $resource;
    }
}
