<?php

namespace App\Http\Middleware;

use App\Models\ChurchToolsMock\CTClientMock;
use Closure;
use CTApi\CTClient;
use CTApi\CTConfig;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChurchToolsMock
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(env('CHURCHTOOLS_MOCK', false) || $request->headers->get('ChurchTools-Mock', false)){
            CTClient::setClient(new CTClientMock());
        }
        return $next($request);
    }
}
