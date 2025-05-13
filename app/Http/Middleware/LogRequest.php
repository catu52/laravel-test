<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Interaction;

class LogRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * Handle the response after the request has been processed.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return void
     */
    public function terminate(Request $request, Response $response): void
    {
        $log = new Interaction();
        $log->user_id = Auth::id(); // Get authenticated user ID
        $log->endpoint = $request->path();
        $log->petition_body = $request->all(); // Capture request body parameters
        $log->response_code = $response->getStatusCode();

        // Capture response body (handle different content types)
        if ($response->headers->get('Content-Type') === 'application/json') {
            $log->response_body = json_decode($response->getContent(), true);
        } else {
            $log->response_body = $response->getContent();
        }

        $log->client_ip = $request->ip();
        $log->timestamp = now();
        $log->save();
    }
}
