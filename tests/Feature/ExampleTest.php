<?php

namespace Dg482\Mrd\Tests\Feature;

use Dg482\Mrd\Builder\Exceptions\ModelNotInstalled;
use Dg482\Mrd\Builder\Form\BaseForms;
use Dg482\Mrd\Resource\Resource;
use Dg482\Mrd\Tests\TestCase;

class ExampleTest extends TestCase
{
    /** @var Resource */
    protected \Dg482\Mrd\Resource\Resource $resource;

    public function setUp()
    {
        parent::setUp();

        $this->resource = new Resource();
    }

    public function testException()
    {
        $this->expectException(ModelNotInstalled::class);

        $this->resource->getForm();
    }

    /**
     * @throws \Exception
     */
    public function testFormBuilder()
    {
        $this->resource->setModel(BaseForms::class);

        $form = $this->resource->getForm();

        $this->assertArrayHasKey('title', $form);
        $this->assertArrayHasKey('form', $form);
        $this->assertArrayHasKey('items', $form);
    }
}
