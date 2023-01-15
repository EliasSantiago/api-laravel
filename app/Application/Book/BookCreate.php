<?php

namespace App\Application\Book;

use App\Http\Interfaces\Book\IBookCreate as IBookCreate;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookCreate implements IBookCreate
{
  function execute(Request $request): object
  {
    $user          = Auth::user();
    $book          = new Book();
    $book->user_id = $user->id;
    $book->name    = $request->name;
    $book->isbn    = $request->isbn;
    $book->value   = $request->value;
    $book->save();
    return $book;
  }
}
