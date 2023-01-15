<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\NotFindException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Interfaces\Book\IBookIndex as IBookIndex;
use App\Http\Interfaces\Book\IBookShow as IBookShow;
use App\Http\Interfaces\Book\IBookDelete as IBookDelete;
use App\Http\Interfaces\Book\IBookCreate as IBookCreate;
use App\Http\Interfaces\Book\IBookUpdate as IBookUpdate;

class BooksController extends Controller
{
  use ApiControllerTrait;

  private $bookIndex;
  private $bookShow;
  private $bookDelete;
  private $bookCreate;
  private $bookUpdate;

  public function __construct(
    IBookIndex $bookIndex,
    IBookShow $bookShow,
    IBookDelete $bookDelete,
    IBookCreate $bookCreate,
    IBookUpdate $bookUpdate
  ) {
    $this->bookIndex  = $bookIndex;
    $this->bookShow   = $bookShow;
    $this->bookDelete = $bookDelete;
    $this->bookCreate = $bookCreate;
    $this->bookUpdate = $bookUpdate;
  }

  public function index(Request $request)
  {
    try {
      return $this->createResponse([
        "books" => $this->bookIndex->execute($request)
      ], 200);
    } catch (\Throwable $th) {
      return $this->createResponse([
        "message" => $th->getMessage(),
        "error"   => true
      ], 500);
    }
  }

  public function store(Request $request)
  {
    try {
      $rulesIputsBook = $this->getRulesInputsBook();
      $validate = $this->validateInputs($request, $rulesIputsBook);

      $responseValidate = $validate->original['data']['content'];

      if (isset($responseValidate->error)) {
        return $validate;
      }

      return $this->createResponse([
        "message"  => 'Book created.',
        "bar"  => $this->bookCreate->execute($request),
      ], 201);
    } catch (\Throwable $th) {
      return $this->createResponse([
        "message" => $th->getMessage(),
        "error"   => true
      ], 500);
    }
  }

  public function show($id)
  {
    try {
      return $this->createResponse([
        "book" => $this->bookShow->execute($id)
      ], 200);
    } catch (NotFindException $e) {
      return $this->createResponse([
        "message" => "Book not found.",
      ], 404);
    } catch (\Throwable $th) {
      return $this->createResponse([
        "message" => $th->getMessage(),
        "error"   => true
      ], 500);
    }
  }

  public function update(Request $request, $id)
  {
    try {
      $rulesIputsBook = $this->getRulesInputsBook();
      $validate = $this->validateInputs($request, $rulesIputsBook);

      $responseValidate = $validate->original['data']['content'];

      if (isset($responseValidate->error)) {
        return $validate;
      }

      return $this->createResponse([
        "message"  => 'Book updated.',
        "book"  => $this->bookUpdate->execute($request, $id)
      ], 201);
    } catch (\Throwable $th) {
      return $this->createResponse([
        "message" => $th->getMessage(),
        "error"   => true
      ], 500);
    }
  }

  public function destroy($id)
  {
    try {
      return $this->createResponse([
        "message"  => $this->bookDelete->execute($id),
      ], 200);
    } catch (NotFindException $e) {
      return $this->createResponse([
        "message" => "Book not found.",
      ], 404);
    } catch (\Throwable $th) {
      return $this->createResponse([
        "message" => $th->getMessage(),
        "error"   => true
      ], 500);
    }
  }

  private function getRulesInputsBook($id = null)
  {
      return [
          'isbn' => ['required', 'integer']
      ];
  }
}
