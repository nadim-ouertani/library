<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksManagementTest extends TestCase
{
    /** @test */
    public function can_add_a_book()
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/book');

        $response->assertStatus(200);
    }
}
