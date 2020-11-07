<?php

namespace Dg482\Mrd\Tests\Feature;

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

        $this->resource = new BaseResource();
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
        $form = [];
        $this->resource->setModel(BaseForms::class);
        try {
            $form = $this->resource->getForm();

            $this->assertArrayHasKey('title', $form);
            $this->assertArrayHasKey('form', $form);
            $this->assertArrayHasKey('items', $form);
        } catch (ModelNotInstalled $exception) {
        }
    }
}
