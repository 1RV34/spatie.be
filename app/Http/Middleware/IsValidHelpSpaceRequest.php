<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IsValidHelpSpaceRequest
{
    public function handle(Request $request, Closure $next)
    {
        $payloadJson = json_encode($request->json());

        $expectedSignature = hash_hmac(
            'sha256',
            $payloadJson,
            config('services.helpSpace.secret')
        );
        info($expectedSignature, $request->header('signature'));
        info(print_r($payloadJson, true));
        $validRequest = $request->header('signature') === $expectedSignature;

        if (! $validRequest) {
            abort(Response::HTTP_FORBIDDEN, 'Invalid HelpSpace request');
        }

        return $next($request);
    }
}
