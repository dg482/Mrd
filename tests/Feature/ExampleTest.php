<?php

namespace Dg482\Mrd\Tests\Feature;

use Dg482\Mrd\Adapters\BaseAdapter;
use Dg482\Mrd\BaseModel;
use Dg482\Mrd\Builder\Exceptions\ModelNotInstalled;
use Dg482\Mrd\Builder\Form\BaseForms;
use Dg482\Mrd\Resource\Resource as BaseResource;
use Dg482\Mrd\Tests\TestCase;
use Exception;

class ExampleTest extends TestCase
{
    /** @var BaseResource */
    private BaseResource $resource;

    public function setUp()
    {
        parent::setUp();

        $this->resource = (new BaseResource);
    }

    /**
     * @throws Exception
     */
    public function testException()
    {
        $this->expectException(ModelNotInstalled::class);
        $this->resource->getForm();
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
            ->setAdapter((new BaseAdapter)->setModel(new BaseModel))// set adapter
            ->setFormModel(new BaseForms);
    }
}
