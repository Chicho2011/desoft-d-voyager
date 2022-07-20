<?php

namespace Desoft\DVoyager\Tests\Feature;

use Desoft\DVoyager\Services\BreadGeneratorServices;
use Desoft\DVoyager\Tests\TestCase;

class BreadGeneratorTest extends TestCase
{
    private BreadGeneratorServices $breadGeneratorServices;

    public function setUp(): void
    {
        parent::setUp();

        $this->breadGeneratorServices = new BreadGeneratorServices;
    }


    public function test_permissions()
    {
        $fieldInfo = [
            'permissions' => [
                'browse' => false
            ]
        ];

        $this->assertFalse($this->breadGeneratorServices->getPermission($fieldInfo, 'browse'));
        $this->assertTrue($this->breadGeneratorServices->getPermission($fieldInfo, 'browser'));
    }
}