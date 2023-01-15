<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\ApiControllerTrait;
use App\Helpers\HelpersTrait;
use App\Enums\UserType;
use App\Models\Role;
use App\Notifications\TokenDeleteAccount;

class AuthController extends Controller
{
  use ApiControllerTrait {}

  public function register(Request $request)
  {
    try {
      $rulesIputsUser = $this->getRulesInputsUser();
      $validateUser = $this->validateInputs($request, $rulesIputsUser);
      $responseValidateUser =  $validateUser->original;

      if (isset($responseValidateUser->error)) {
        return $validateUser;
      }

      if ($request->password !== $request->password_confirmation) {
        return $this->createResponse([
          "message" => 'As senhas devem ser iguais!'
        ], 422);
      }

      $user = [
        "name"     => $request->name,
        "email"    => $request->email,
        "password" => Hash::make($request->password),
      ];

      $user = User::create($user);
      $token =  $user->createToken('apilaravel')->accessToken;

      return $this->createResponse([
        "user"  => $user,
        "token" => $token
      ], 201);
    } catch (\Throwable $th) {
      return $this->createResponse([
        "message" => $th->getMessage(),
        "error"   => true
      ], 500);
    }
  }

  public function login(Request $request)
  {
    try {
      $rulesLogin       = $this->getRulesInputsLogin();
      $validate         = $this->validateInputs($request, $rulesLogin);
      $responseValidate = $validate->original;

      if (isset($responseValidate->error)) {
        return $validate;
      }

      $credentials = $request->only('email', 'password');
      $user = User::where('email', $credentials['email'])->first();

      if (!$user) {
        return $this->createResponse([
          'message' => [
            'login' => 'Usuário ou senha inválidos.'
          ],
          'error'   => true
        ], 401);
      }

      if (!Hash::check($credentials['password'], $user->password)) {
        return $this->createResponse([
          'message' => [
            'login' => 'Usuário ou senha inválidos.'
          ],
          'error'   => true
        ], 401);
      }

      $user->save();
      $tokenResult = $user->createToken('apibolao');
     
      return $this->createResponse([
        'user' => $user,
        'token_type' => 'Bearer',
        'access_token' => $tokenResult->accessToken,
      ], 200);
    } catch (\Exception $e) {

      return $this->createResponse([
        'message' => $e->getMessage(),
        'error'   => true
      ], 500);
    }
  }

  public function logout(Request $request)
  {
    Auth::logout();
    return $this->createResponse([
        'message' => 'Deslogado com sucesso!'
    ], 200);
  }

  public function user(Request $request)
  {
    return response()->json($request->user());
  }

  private function getRulesInputsUser($id = null)
  {
    return [
      'name'                  => 'required',
      'email'                 => 'required|email|unique:users',
      'password'              => 'required',
      'password_confirmation' => 'required|same:password'
    ];
  }

  private function getRulesInputsLogin()
  {
    return [
      'email'     => 'required|email',
      'password'  => 'required|string',
    ];
  }
}
