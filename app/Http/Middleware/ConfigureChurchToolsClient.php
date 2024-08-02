<?php

namespace App\Http\Middleware;

use Closure;
use CTApi\CTConfig;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigureChurchToolsClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $url = $request->headers->get('ChurchTools-Url');
        if($url !== null){
            CTConfig::setApiUrl($url);
        }else{
            // TODO: Throw Exception and handle in frontend.
        }
        $response = $next($request);

        $response->headers->set('ChurchTools-Url', $url);
        return $response;
    }
}
