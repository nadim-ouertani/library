<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksManagementTest extends TestCase
{
    use RefreshDatabase;
    protected $user, $admin;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->admin = User::factory()->create([
            'role'=>'admin',
        ]);
        $this->user = User::factory()->create();
    }

//Admin book management
    /** @test */
    public function admin_can_add_a_book()
    {
        $this->actingAs($this->admin);

        $this->assertEquals('admin', $this->admin->role);
        $response = $this->post('/books', $this->validData());
        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());

    }
    /** @test */
    public function admin_can_update_a_book()
    {
        $this->actingAs($this->admin);
        $this->post('/books', $this->validData());
        $book = Book::first();

        $this->assertEquals('admin', $this->admin->role);
        $response = $this->patch($book->path(),array_merge($this->validData(), ['title'=> 'Updated title', 'author' => 'Updated author']));
        $this->assertEquals('Updated title', Book::first()->title);
        $this->assertEquals('Updated author', Book::first()->author);
        $response->assertRedirect($book->fresh()->path());
    }
    /** @test */
    public function admin_can_delete_a_book()
    {
        $this->actingAs($this->admin);
        $this->post('/books', $this->validData());
        $book = Book::first();

        $this->assertEquals('admin', $this->admin->role);
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());
        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }

//Only admin can manage the books
    /** @test */
    public function only_admin_can_add_a_book()
    {
        $this->actingAs($this->user);
        $response = $this->post('/books', $this->validData());

        $this->assertEquals('user', $this->user->role);
        $this->assertCount(0, Book::all());
        $response->assertUnauthorized();

    }
    /** @test */
    public function only_admin_can_update_a_book()
    {
        $book = Book::factory()->create($this->validData());
        $this->actingAs($this->user);
        $this->assertEquals('user', $this->user->role);
        $response = $this->patch($book->path(),array_merge($this->validData(), ['title'=> 'Updated title', 'author' => 'Updated author']));
        $this->assertNotEquals('Updated title', Book::first()->title);
        $this->assertNotEquals('Updated author', Book::first()->author);
        $response->assertUnauthorized();

    }
    /** @test */
    public function only_admin_can_delete_a_book()
    {
        $book = Book::factory()->create($this->validData());
        $this->actingAs($this->user);
        $this->assertEquals('user', $this->user->role);
        $response = $this->delete($book->path());
        $this->assertCount(1, Book::all());
        $response->assertUnauthorized();

    }

//Authorized user can get book/ books && checkin/ checkout books
    /** @test */
    public function authorized_user_can_see_all_books()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user);

        Book::factory(3)->create();

        $response = $this->get('/books');
        $response->assertJsonCount(3);
        $response->assertOk();
    }
    /** @test */
    public function authorized_user_can_see_a_specific_book()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user);

        $book = Book::factory()->create();
        $response = $this->get($book->path());
        $response->assertJson([
            "id"=> $book->id,
            "title"=> $book->title,
            "author"=> $book->author,
            "year"=> $book->year,
            "created_at"=> $book->created_at->jsonSerialize(),
            "updated_at"=> $book->updated_at->jsonSerialize(),
        ]);
        $response->assertOk();
    }
    /** TODO */
    public function authorized_user_can_checkout_book()
    {

    }
    /** TODO */
    public function authorized_user_can_checkin_book()
    {

    }

//Only authorized user can get book/ books
    /** @test */
    public function only_authorized_user_can_see_all_books()
    {
        Book::factory(3)->create();
        $response = $this->get('/books');
        $response->assertUnauthorized();
    }
    /** @test */
    public function only_authorized_user_can_see_a_specific_book()
    {
        $book = Book::factory()->create();
        $response = $this->get($book->path());
        $response->assertUnauthorized();
    }
    /** TODO */
    public function only_authorized_user_can_checkout_book()
    {

    }
    /** TODO */
    public function only_authorized_user_can_checkin_book()
    {

    }


//Required data
    /** @test */
    public function field_required()
    {
        $this->actingAs($this->admin);
        collect(['title','author', 'year'])->each(function($field) {
            $response = $this->post('/books', array_merge($this->validData(), [$field => '']));
            $response->assertSessionHasErrors($field);
            $this->assertCount(0, Book::all());
        });
    }
//Helper
    private function validData() :array {
        return [
            'title'=>'testing book',
            'author'=>'nadim',
            'year' => '1991',
        ];
    }
}

