<?php

namespace App\Http\Middleware;

use App\DTOs\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class ApiExceptionHandler
{
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (ValidationException $e) {
            return ApiResponse::error('Validation error', $e->errors(), 400);
        } catch (TokenExpiredException $e) {
            return ApiResponse::error('Token has expired', null, 401);
        } catch (TokenInvalidException $e) {
            return ApiResponse::error('Token is invalid', null, 401);
        } catch (JWTException $e) {
            return ApiResponse::error('Token not provided', null, 401);
        } catch (\Exception $e) {
            return ApiResponse::serverError('Internal server error');
        }
    }
}
