<?php

namespace Dg482\Mrd\Builder\Form\Fields;

use Closure;
use Dg482\Mrd\BaseModel;
use Dg482\Mrd\Builder\Form\Fields\Interfaces\FileStorageInterface;
use Dg482\Mrd\Model;

/**
 * Class File
 * @package Dg482\Mrd\Builder\Form\Fields
 */
class File extends FieldEnum
{


    /** @var string */
    const FIELD_TYPE = 'file';

    /** @var string */
    const MIMES_IMAGE = 'jpeg,bmp,png,jpg';

    /** @var string */
    const MIMES_DOCUMENT = 'xls,xlst,doc,docx,pdf';

    /** @var string */
    protected string $uploadAction = 'api/form/upload';

    /** @var int */
    protected int $type = 0;

    /** @var null|Closure */
    protected ?Closure $store = null;

    /**
     * @return array
     */
    public function getFormField(): array
    {
        $result = parent::getFormField();

        $result['multiple'] = $this->isMultiple();
        $result['action'] = $this->getUploadAction();
        $result['data'] = $this->getData();

        return $result;
    }

    /**
     * @return string
     */
    public function getUploadAction(): string
    {
        return $this->uploadAction;
    }

    /**
     * @param  string  $uploadAction
     * @return File
     */
    public function setUploadAction(string $uploadAction): File
    {
        $this->uploadAction = $uploadAction;

        return $this;
    }


    /**
     * @param  BaseModel  $model
     * @param  BaseModel  $relation
     * @return $this
     */
    public function setFieldRelation(BaseModel $model, BaseModel $relation): Field
    {
        if (($relation instanceof Model) && isset($relation->id) && $relation->id) {
            $this
                ->setData([
                    'owner' => $model->id ?? 0,
                    'type' => $this->getType(),
                    'form' => $this->request('form'),
                    'field' => $this->getField(),
                    'relation' => $relation,
                ])->setFieldValue('');

            $this->relation = $relation;
        }

        return $this;
    }

    /**
     * @param $value
     * @return Field
     */
    public function setFieldValue($value): Field
    {
        return parent::setFieldValue($value);
    }

    /**
     * @return Closure|null
     */
    public function getStore(): ?Closure
    {
        return $this->store;
    }

    /**
     * @param  Closure|null  $store
     * @return File
     */
    public function setStore(?Closure $store): File
    {
        $this->store = $store;

        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param  int  $type
     * @return File
     */
    public function setType(int $type): File
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param  $file
     * @return mixed
     */
    public function storeFile($file)
    {
        return $this->getStore()($file);
    }
}
