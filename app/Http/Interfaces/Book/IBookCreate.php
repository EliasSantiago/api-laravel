<?php

namespace App\Http\Interfaces\Book;

use Illuminate\Http\Request;

interface IBookCreate
{
    function execute(Request $request) : object;
}