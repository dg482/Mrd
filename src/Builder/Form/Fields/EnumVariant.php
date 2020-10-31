<?php

namespace Dg482\Mrd\Builder\Form\Fields;

/**
 * Class EnumVariant
 * @package Dg482\Mrd\Builder\Form\Fields
 */
class EnumVariant
{
    /** @var string */
    const NAME = 'value';

    /** @var string */
    const USER_ID = 'user_id';

    /** @var string */
    public string $model = 'field_enum_variant';

    /** @var array */
    protected $fillable = [self::NAME, self::USER_ID];

    /**
     * @param  int  $id
     * @param  string  $name
     * @return $this
     */
    public function make(int $id, string $name)
    {
        $this->id = (int) $id;
        $this->{self::NAME} = $name;
        $this->{self::USER_ID} = auth()->check() ? auth()->user()->id : 0;

        return $this;
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        return [
            'id' => $this->id,
            self::NAME => $this->{self::NAME},
        ];
    }
}
