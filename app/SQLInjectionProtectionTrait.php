<?php

namespace App;

use Illuminate\support\Facades\Log ;

trait SQLInjectionProtectionTrait
{
     private $sqlPatterns = [
        '/\bUNION\b.*\bSELECT\b/i',
        '/\bDROP\b/i',
        '/\bDELETE\b/i',
        '/\bINSERT\b/i',
        '/\bUPDATE\b/i',
        '/\bALTER\b/i',
        '/\bCREATE\b/i',
        '/\bEXEC\b/i',
        '/\bXP_/i',
        '/--/',
        '/\/\*/',
        '/\#/',
        '/;/',
        '/\bOR\b.*=.*=/i',
        '/\bAND\b.*=.*=/i',
        '/\bSLEEP\b/i',
        '/\bWAITFOR\b/i'
    ];
    
   
    protected function sanitizeInput($input)
    {
        if (is_array($input)) {
            return array_map([$this, 'sanitizeInput'], $input);
        }
        
        if (is_string($input)) {
            $original = $input;
            
            foreach ($this->sqlPatterns as $pattern) {
                if (preg_match($pattern, $input)) {
                    Log::warning('SQL Injection pattern detected', [
                        'pattern' => $pattern,
                        'input' => $input
                    ]);
                    
                    // إزالة النمط الخطير
                    $input = preg_replace($pattern, '', $input);
                }
            }
            
           
            if ($original !== $input) {
                Log::info('Input sanitized', [
                    'original' => $original,
                    'sanitized' => $input
                ]);
            }
        }
        
        return $input;
    }
    
   
    protected function validateInput($input, $type = 'string')
    {
        if (is_null($input)) {
            return true;
        }
        
        switch ($type) {
            case 'integer':
                return filter_var($input, FILTER_VALIDATE_INT) !== false;
                
            case 'float':
            case 'decimal':
                return filter_var($input, FILTER_VALIDATE_FLOAT) !== false;
                
            case 'email':
                return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
                
            case 'boolean':
                return is_bool($input) || in_array($input, [0, 1, '0', '1', true, false]);
                
            case 'string':
               
                foreach ($this->sqlPatterns as $pattern) {
                    if (preg_match($pattern, $input)) {
                        return false;
                    }
                }
                return true;
                
            default:
                return true;
        }
    }
    
   
    protected function sanitizeNumber($input)
    {
        if (is_numeric($input)) {
            return $input;
        }
        
      
        $sanitized = preg_replace('/[^0-9.]/', '', $input);
        
        if ($sanitized !== $input) {
            Log::warning('Number sanitized', [
                'original' => $input,
                'sanitized' => $sanitized
            ]);
        }
        
        return $sanitized;
    }
}