<?php

namespace App\Traits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ApiResponser
{
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json(['message' => $message, 'code' => $code,'error'=>true], $code);
    }
    protected function showAll(Collection $collection, $code=200,$message='')
    {
        return $this->successResponse(['data' => $collection,'error'=>false,'message' => $message], $code);
    }
    protected function showOne(Model $model, $code=200,$message='')
    {
        return $this->successResponse(['data' => $model,'error'=>false,'message' => $message], $code);
    }
   
}
