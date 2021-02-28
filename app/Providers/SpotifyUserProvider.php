<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use App\Models\SpotifyUser;

class SpotifyUserProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    static function getTokens(SpotifyUser $user)
    {
        if (!$user->authorization)
            return false;

        return Http::asForm()
            ->withBasicAuth(
                env('SPOTIFY_CLIENT_ID'),
                env('SPOTIFY_CLIENT_SECRET')
            )
            ->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'authorization_code',
                'code' => $user->authorization,
                'redirect_uri' => env('APP_URL') . '/api/register/authorization'
            ])
            ->json();
    }

    static function get(SpotifyUser $user)
    {
        if (!$user->token)
            return false;

        return Http::asForm()
            ->withToken($user->token)
            ->get('https://api.spotify.com/v1/me')
            ->json();
    }

    static function getRefreshedTokens(SpotifyUser $user)
    {
        if (!$user->refresh_token)
            return false;

        return Http::asForm()
            ->withBasicAuth(
                env('SPOTIFY_CLIENT_ID'),
                env('SPOTIFY_CLIENT_SECRET')
            )
            ->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $user->refresh_token
            ])
            ->json();
    }
}
