<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class BooksController extends Controller
{
    public function show()
    {
        return response (Book::all(), '200');
//        if (Auth::check()) {
//            return response (Book::all(), '200');
//        } else {
//            return response (null,'401');
//        }
    }

    public function show_a_book(Book $book)
    {
        return response ($book, '200');
//        if (Auth::check()) {
//            return response ($book, '200');
//        } else {
//            return response (null,'401');
//        }
    }

    public function store()
    {
        if (Auth::user()->role == 'admin')
            return redirect('api' . Book::create($this->validateRequest())->path());

        return response(null, '401');
    }

    public function update(Book $book)
    {
        if (Auth::user()->role == 'admin') {
            $book->update($this->validateRequest());
            return redirect($book->path());
        } else {
            return response(null, '401');
        }
    }

    public function destroy(Book $book)
    {
        if (Auth::user()->role == 'admin') {
            $book->delete();
            return redirect('api/books');
        } else {
            return response(null, '401');
        }
    }

    /**
     * @return array
     */
    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
            'author' => 'required',
            'year' => 'required',
        ]);
    }
}
