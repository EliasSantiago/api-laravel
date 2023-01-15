<?php

namespace App\Http\Interfaces\Book;

interface IBookDelete
{
    function execute(int $id);
}