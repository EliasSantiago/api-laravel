<?php

namespace App\Http\Interfaces\Book;

use Illuminate\Http\Request;

interface IBookIndex
{
    function execute(Request $request);
}