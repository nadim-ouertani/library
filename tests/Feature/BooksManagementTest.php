<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksManagementTest extends TestCase
{
    /** @test */
    use RefreshDatabase;
    public function can_add_a_book()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/books', [
            'title'=>'testing book',
            'author'=>'nadim',
        ]);

        $response->assertOk();
        $this->assertCount(1, Book::all());

    }
}
