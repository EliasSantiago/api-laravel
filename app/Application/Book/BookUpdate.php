<?php

namespace App\Application\Book;

use App\Exceptions\NotFindException;
use App\Http\Interfaces\Book\IBookUpdate as IBookUpdate;
use App\Models\Book;
use Illuminate\Http\Request;

class BookUpdate implements IBookUpdate
{
  function execute(Request $request, int $id)
  {
    $book = Book::find($id);
    if (!$book) {
      throw new NotFindException('Book not found.');
    }
    $book->name    = $request->name;
    $book->isbn    = $request->isbn;
    $book->value   = $request->value;
    $book->save();
    return $book;
  }
}
