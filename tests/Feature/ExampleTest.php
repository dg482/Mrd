<?php

namespace Dg482\Mrd\Tests\Feature;

use Dg482\Mrd\Adapters\BaseAdapter;
use Dg482\Mrd\BaseModel;
use Dg482\Mrd\Builder\Exceptions\FieldNotFound;
use Dg482\Mrd\Builder\Exceptions\ModelNotInstalled;
use Dg482\Mrd\Builder\Form\BaseForms;
use Dg482\Mrd\Builder\Form\Fields\Badge;
use Dg482\Mrd\Builder\Form\Fields\Boolean;
use Dg482\Mrd\Builder\Form\Fields\Date;
use Dg482\Mrd\Builder\Form\Fields\Datetime;
use Dg482\Mrd\Builder\Form\Fields\FieldFromModel;
use Dg482\Mrd\Builder\Form\Fields\File;
use Dg482\Mrd\Builder\Form\Fields\Integer;
use Dg482\Mrd\Builder\Form\Fields\Text;
use Dg482\Mrd\Resource\Resource as BaseResource;
use Dg482\Mrd\Tests\TestCase;
use Exception;

class ExampleTest extends TestCase
{
    /** @var BaseResource */
    private BaseResource $resource;

    /** @var BaseModel */
    private BaseModel $model;

    /** @var BaseForms */
    private BaseForms $form;

    public function setUp()
    {
        parent::setUp();

        $this->resource = (new BaseResource);
        $this->model = (new BaseModel);
        $this->form = new BaseForms($this->model, $this->resource);
    }

    /**
     * @throws Exception
     */
    public function testFormBuilder()
    {
        $this->initResource();

        $form = $this->resource->getForm();

        $this->assertArrayHasKey('title', $form);
        $this->assertArrayHasKey('form', $form);
        $this->assertArrayHasKey('items', $form);
    }

    /**
     * @depends testFormBuilder
     * @throws Exception
     */
    public function testTableBuilder()
    {
        $this->initResource();

        $table = $this->resource->getTable();

        $this->assertArrayHasKey('columns', $table);
        $this->assertArrayHasKey('data', $table);
        $this->assertArrayHasKey('pagination', $table);
    }


    protected function initResource()
    {
        $this->resource = $this->resource
            ->setAdapter(
                (new BaseAdapter($this->model, $this->initFields()))
            )
            ->setFormModel($this->form);
    }

    /**
     * @return array
     */
    protected function initFields(): array
    {
        $fields = [];
        $fieldFormModel = (new FieldFromModel(new BaseModel));

        try {
            array_push($fields, $fieldFormModel->getField([
                'id' => 'id',
            ]));

            $text = $fieldFormModel->getField([
                'id' => 'name',
                'type' => Text::FIELD_TYPE,
            ]);

            $text->setBadge((new Badge)->make('Danger', Badge::TYPE_DANGER));
            $text->pushBadge((new Badge)->make('Warning', Badge::TYPE_WARNING));

            array_push($fields, $text);
            array_push($fields, $fieldFormModel->getField([
                'id' => 'photo',
                'type' => File::FIELD_TYPE,
            ]));
            array_push($fields, $fieldFormModel->getField([
                'id' => 'dob',
                'type' => Date::FIELD_TYPE,
            ]));
            array_push($fields, $fieldFormModel->getField([
                'id' => 'stars',
                'type' => Integer::FIELD_TYPE,
            ]));
            array_push($fields, $fieldFormModel->getField([
                'id' => 'last_visit',
                'type' => Datetime::FIELD_TYPE,
            ]));
            array_push($fields, $fieldFormModel->getField([
                'id' => 'is_blocked',
                'type' => Boolean::FIELD_TYPE,
            ]));
        } catch (FieldNotFound $e) {
            return ['message' => $e->getMessage()];
        }


        return $fields;
    }
}
