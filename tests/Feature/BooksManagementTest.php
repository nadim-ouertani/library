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
//        $this->withoutExceptionHandling();
        $response = $this->post('/books', [
            'title'=>'testing book',
            'author'=>'nadim',
        ]);

        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response->assertRedirect('/books/' . $book->id);

    }

    /** @test */
    public function a_title_is_required()
    {
        $response = $this->post('/books', [
            'title'=>'',
            'author'=>'nadim',
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function an_author_is_required()
    {
        $response = $this->post('/books', [
            'title'=>'testing book',
            'author'=>'',
        ]);

        $response->assertSessionHasErrors('author');
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
}
