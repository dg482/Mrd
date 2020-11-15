<?php

namespace Dg482\Mrd\Builder\Form\Fields;

/**
 * Class EnumVariant
 * @package Dg482\Mrd\Builder\Form\Fields
 */
class EnumVariant
{
    /** @var int|null $id*/
    public ?int $id;

    /** @var string */
    const NAME = 'value';

    /** @var string */
    const USER_ID = 'user_id';

    /** @var string */
    public string $model = 'field_enum_variant';

    /**
     * @param  int  $id
     * @param  string  $name
     * @return $this
     */
    public function make(int $id, string $name)
    {
        $this->id = (int)$id;
        $this->{self::NAME} = $name;

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
