<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\SpotifyUser;
use App\Providers\SpotifyUserProvider;

class Authorization extends Controller
{
    public function test(Request $request)
    {
        $return = Http::asForm()
            ->withBasicAuth(
                env('SPOTIFY_CLIENT_ID'),
                env('SPOTIFY_CLIENT_SECRET')
            )
            ->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'authorization_code',
                'code' => 'AQAZee2BD7WTtAR3KviN1RJgBjC_tZcvXGCvLxEXyr-Z3dfTHU9aQDXb_6Q3fRjRAbDVHhLlS11MXj3Xv9hKlrNS_Ik7ZKAsgECL5v8TOIthD5tPeFG5eepiUxSGM7ddOjLntkSwyGm9rxDGWpRcVE72SqckahcCqZzv_LyIHMhSk2vBV56xbe_YZYGDcSFcQbMErB_vGWGaI3PEP-zsI-7G-JxsgjRiliSUIFN4DlXt93FZ_8nPSUwlPlwuZl7rO9HY3LB3eg',
                'redirect_uri' => 'https://genderfy.test/return.php'
            ]);


        return $return;
    }

    public function return(Request $request)
    {
        return $request;
    }

    public function register(Request $request)
    {
        $data = $request->all();

        $spotify_user = SpotifyUser::firstOrNew([
            'authorization' => $data['code']
        ]);

        if (!$spotify_user) {
            return response()->json('Error on saving', 400);
        }

        if (!$spotify_user->token) {
            $tokens = SpotifyUserProvider::getTokens($spotify_user);

            if (!$tokens || isset($tokens['error']))
                return response()->json('Error to get token', 400);

            $spotify_user->fill([
                'token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
            ]);

            if (!$spotify_user->save())
                return response()->json('Error on saving', 400);
        }

        $user = SpotifyUserProvider::get($spotify_user);

        if (isset($user['error'])) {
            return response()->json($user, 400);
        }

        if (!$spotify_user->spotify_id) {
            $another = SpotifyUser::where('spotify_id', $user['id'])->first();

            if ($another) {
                $another->fill([
                    'authorization' => $data['code'],
                    'token' => $spotify_user->token,
                    'refresh_token' => $spotify_user->refresh_token,
                ]);

                $another->save();
                $spotify_user->delete();
                $spotify_user = $another;
            } else {
                $spotify_user->fill([
                    'spotify_id' => $user['id']
                ]);

            $spotify_user->save();
        }
        }

        return response()->json($spotify_user, 200);
    }
}
