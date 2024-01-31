<?php

namespace Tests\Unit;

use Tests\TestCase;

class DemoTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function test_demo()
    {

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
