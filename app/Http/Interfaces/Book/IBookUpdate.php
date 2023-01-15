<?php

namespace App\Http\Interfaces\Book;

use Illuminate\Http\Request;

interface IBookUpdate
{
    function execute(Request $request, int $id);
}