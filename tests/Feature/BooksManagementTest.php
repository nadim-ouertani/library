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
        $response->assertRedirect($book->path());

    }

    /** @test */
    public function field_required()
    {
        collect(['title','author', 'year'])->each(function($field) {
            $response = $this->post('/books', array_merge($this->validData(), [$field => '']));
            $response->assertSessionHasErrors($field);
            $this->assertCount(0, Book::all());
        });
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        $this->post('/books', $this->validData());

        $book = Book::first();
        $response = $this->patch($book->path(),array_merge($this->validData(), ['title'=> 'Updated title', 'author' => 'Updated author']));
        $this->assertEquals('Updated title', Book::first()->title);
        $this->assertEquals('Updated author', Book::first()->author);
        $response->assertRedirect($book->fresh()->path());
    }

    /** @test */
    public function a_book_can_be_deleted()
    {
        $this->post('/books', $this->validData());

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());
        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }

    private function validData() {
        return [
            'title'=>'testing book',
            'author'=>'nadim',
            'year' => '1991',
        ];
    }
}

