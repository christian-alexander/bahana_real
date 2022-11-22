<?php

namespace Modules\RestAPI\Http\Middleware;

use App\GlobalSetting;
use Closure;
use Froiden\RestAPI\Exceptions\UnauthorizedException;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthMiddleware
{

    public function handle($request, Closure $next)
    {

        config(['auth.defaults.guard' => 'api']);
        // Set JWT SECRET KEY HERE
        config(['jwt.secret' => config('restapi.jwt_secret')]);
        config(['app.debug' => config('restapi.debug')]);

        // get global setting
        $setting = GlobalSetting::first();

        if (env('APP_ENV') === 'testing') {
            JWTAuth::setRequest($request);
        }

        // Do not apply this middleware to OPTIONS request
        if ($request->getMethod() !== 'OPTIONS') {
            $token = $request->header('Authorization', '');
            if (Str::startsWith($token, 'Bearer ')) {
                $token =  Str::substr($token, 7);
            }
            try {
                if (!$user = api_user()) {
                    throw new UnauthorizedException('User not found', null, 403, 403, 2006);
                }
                if ($setting->single_sign_on == 1) {
                    if ($user->token != $token) {
                        throw new UnauthorizedException('Token is invalid', null, 403, 403, 2008);
                    }
                }
            } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                throw new UnauthorizedException('Token has expired', null, 403, 403, 2007);
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                throw new UnauthorizedException('Token is invalid', null, 403, 403, 2008);
            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                throw new UnauthorizedException('Token is required', null, 403, 403, 2009);
            }
        }
        return $next($request);
    }
}
