<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use Exception;

trait ApiControllerTrait
{
    public function validateInputs(Request $request, $rules, $messages = null)
    {      
        $validate = Validator::make($request->all(), $rules);
        if($validate->fails())
        {
            $errors['messages'] = $validate->errors();
            $errors['error']    = true;
            return $this->createResponse($errors, 422);
        }
        return $this->createResponse($validate);
    }

    /**
     * @param   $result
     * @return \Illuminate\Http\Response
     */

    protected function createResponse($result, $statusCode = null)
    {
        $response = (Object) $result;
        $statusCode = is_null($statusCode) ? app('Illuminate\Http\Response')->status() : $statusCode;

        if(property_exists($response, 'collects')){
            $data['content']       = (Array) $result->items();
            $data['status']        = $statusCode; //alterar a atribuição de status

            $data['links'] = [
                'first_page_url' => $response->url(1) ,
                'last_page_url'  => $response->url($response->lastPage()),
                'next_page_url'  => $response->nextPageUrl(),
                'prev_page_url'  => $response->previousPageUrl(),
            ];
            $data['meta'] = [
                'current_page'   => $response->currentPage(),
                'from'           => $response->firstItem(),
                'last_page'      => $response->lastPage(),
                'path'           => $response->resolveCurrentPath(),
                'per_page'       => $response->perPage(),
                'to'             => $response->lastItem(),
                'total'          => $response->total(),
            ];
        }
        else{
            $data['content']       = $response;
            $data['status']        = $statusCode;
        }
            
        return response()
        ->json(['data' => $data], $statusCode, [], JSON_UNESCAPED_UNICODE)
        ->setStatusCode($statusCode);
    }
}