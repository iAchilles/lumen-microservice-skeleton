<?php
namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * ApiMiddleware class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class ApiMiddleware
{

    public function handle(Request $request, \Closure $next)
    {
        $secret     = $request->bearerToken();
        $nodeSecret = config('api.secret');
        $ips        = config('api.ip');

        if (!is_null($ips)) {
            if (!in_array($request->getClientIp(), $ips)) {
                $content = [
                    'status' => 'error',
                    'error'  => [
                        'code'    => Response::HTTP_UNAUTHORIZED,
                        'message' => Response::$statusTexts[ Response::HTTP_UNAUTHORIZED ]
                    ]
                ];

                return new Response($content, Response::HTTP_UNAUTHORIZED);
            }
        }

        if ($secret !== $nodeSecret) {
            $content = [
                'status' => 'error',
                'error'  => [
                    'code'    => Response::HTTP_UNAUTHORIZED,
                    'message' => Response::$statusTexts[ Response::HTTP_UNAUTHORIZED ]
                ]
            ];

            return new Response($content, Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }

}
