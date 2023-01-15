<?php

namespace App\Application\Book;

use App\Exceptions\NotFindException;
use App\Http\Interfaces\Book\IBookDelete as IBookDelete;
use App\Models\Book;

class BookDelete implements IBookDelete
{
  function execute(int $id)
  {
    $book = Book::find($id);
    if (!$book) {
      throw new NotFindException();
    }
    $book->delete();
    return 'Book deleted.';
  }
}
