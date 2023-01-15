<?php

namespace App\Application\Book;

use App\Exceptions\NotFindException;
use App\Http\Interfaces\Book\IBookShow;
use App\Models\Book;

class BookShow implements IBookShow
{
    function execute(int $id)
    {
        $book = Book::where('id', $id)->first();
        if (!$book) {
            throw new NotFindException();
        }
        return $book;
    }
}