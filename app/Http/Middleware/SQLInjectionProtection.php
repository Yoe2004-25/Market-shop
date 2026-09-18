<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class SQLInjectionProtection
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         $allInputs = $request->all();
        
        foreach ($allInputs as $key => $value)
         {
            if (is_string($value) && !empty($value)) {
               
                if ($this->detectSQLInjection($value)) {
                    Log::critical('SQL Injection Attempt Blocked', [
                        'ip' => $request->ip(),
                        'key' => $key,
                        'value' => $value,
                        'url' => $request->fullUrl(),
                        'method' => $request->method()
                    ]);
                    
                   
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid input detected. Security violation.',
                        'code' => 'SECURITY_VIOLATION'
                    ], 403);
                }
            }
        }
        
        return $next($request);
    }
    
    private function detectSQLInjection($input)
    {
        $patterns = [
            '/\bUNION\b.*\bSELECT\b/i',
            '/\bDROP\b.*\bTABLE\b/i',
            '/\bDELETE\b.*\bFROM\b/i',
            '/\bINSERT\b.*\bINTO\b/i',
            '/\bUPDATE\b.*\bSET\b/i',
            '/--/',
            '/\/\*/',
            '/\#/',
            '/;/',
            '/\bOR\b.*=.*=/i',
            '/\bAND\b.*=.*=/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }
}