<?php

namespace App\Application\Book;

use App\Http\Interfaces\Book\IBookIndex as IBookIndex;
use App\Models\Book;
use Illuminate\Http\Request;

class BookIndex implements IBookIndex
{
  function execute(Request $request)
  {
    $userId = $request->user_id;
    $isbn    = $request->isbn;
    $value   = $request->value;
    $query   = Book::query();
    $query->when($userId, function ($query) use ($userId) {
      $query->where('userId', $userId);
    });
    $query->when($isbn, function ($query) use ($isbn) {
      $query->where('isbn', $isbn);
    });
    $query->when($value, function ($query) use ($value) {
      $query->where('value', $value);
    });
    return $query->with('users')->orderBy('id', 'Desc')->paginate(15);
  }
}
