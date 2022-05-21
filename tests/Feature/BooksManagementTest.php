<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_add_a_book()
    {
        $response = $this->post('/books', $this->validData());

        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response->assertRedirect('/books/' . $book->id);

    }

    /** @test */
    public function field_required()
    {
        collect(['title','author'])->each(function($field) {
            $response = $this->post('/books', array_merge($this->validData(), [$field => '']));
            $response->assertSessionHasErrors($field);
            $this->assertCount(0, Book::all());
        });
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        $this->post('/books', [
            'title'=>'Testing book',
            'author'=>'nadim',
        ]);

        $book = Book::first();

        $response = $this->patch('/books/' . $book->id,[
            'title'=>'Testing book updated',
            'author'=>'nadim updated'
        ]);
        $this->assertEquals('Testing book updated', Book::first()->title);
        $this->assertEquals('nadim updated', Book::first()->author);
        $response->assertRedirect('/books/' . $book->id);
    }

    /** @test */
    public function a_book_can_be_deleted()
    {
        $this->withoutExceptionHandling();
        $this->post('/books', [
            'title'=>'Testing book',
            'author'=>'nadim',
        ]);

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete('/books/' . $book->id);
        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books/' . $book->id);
    }

    private function validData() {
        return [
            'title'=>'testing book',
            'author'=>'nadim',
        ];
    }
}

