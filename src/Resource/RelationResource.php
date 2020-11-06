<?php

namespace Dg482\Mrd\Resource;

use Dg482\Mrd\Builder\Form\Fields\Table;

/**
 * Class RelationResource
 * @package Dg482\Mrd\Resource
 */
class RelationResource extends Resource
{
    use ResourceTrait;

    /** @var string */
    protected string $field = Table::FIELD_TYPE;

    /** @var string */
    protected string $relationName = '';

    /** @var null|array */
    protected ?array $collection = null;

    /** @var string */
    protected string $form = '';

    /**
     * @return array
     */
    public function getCollection(): array
    {
        return $this->collection ?? [];
    }

    /**
     * @param  array|null  $collection
     * @return $this
     */
    public function setCollection(?array $collection): RelationResource
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param  string  $field
     * @return RelationResource
     */
    public function setField(string $field): RelationResource
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @return string
     */
    public function getRelationName(): string
    {
        return $this->relationName;
    }

    /**
     * @param  string  $relationName
     * @return RelationResource
     */
    public function setRelationName(string $relationName): RelationResource
    {
        $this->relationName = $relationName;

        return $this;
    }
}
