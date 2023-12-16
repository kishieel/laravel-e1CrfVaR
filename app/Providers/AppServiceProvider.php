<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\AccessToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;
use Knuckles\Camel\Extraction\ExtractedEndpointData;
use Knuckles\Scribe\Scribe;
use Laravel\Sanctum\Sanctum;
use ReflectionFunctionAbstract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Sanctum::ignoreMigrations();
        Sanctum::usePersonalAccessTokenModel(AccessToken::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (class_exists(Scribe::class)) {
            Scribe::beforeResponseCall(function (Request $request, ExtractedEndpointData $endpointData) {
                $user = User::first();
                $token = $user->createToken('SCRIBE_TOKEN')->plainTextToken;

                $request->headers->set('Authorization', "Bearer $token");
                $request->server->set('HTTP_AUTHORIZATION', "Bearer $token");

                Sanctum::actingAs($user, ['*']);
            });
            Scribe::instantiateFormRequestUsing(function (string $formRequestClassName, Route $route, ReflectionFunctionAbstract $method) {
                $user = User::first();

                /** @var \Illuminate\Foundation\Http\FormRequest $formRequest */
                $formRequest = new $formRequestClassName();
                $formRequest->setUserResolver(fn () => $user);

                return $formRequest;
            });
        }
    }
}
