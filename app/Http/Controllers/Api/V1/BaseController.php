<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BaseController extends Controller
{
    use ApiResponse;

    protected function validateRequest(Request $request, array $rules, array $messages = [])
    {
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        return true;
    }

    protected function handleException(\Exception $e)
    {
        \Log::error($e->getMessage());
        
        if (config('app.debug')) {
            return $this->errorResponse($e->getMessage(), 500);
        }

        return $this->errorResponse('An unexpected error occurred', 500);
    }

    protected function handleModelNotFound($message = null)
    {
        return $this->notFoundResponse($message ?? 'Resource not found');
    }
} 