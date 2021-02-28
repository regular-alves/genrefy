<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

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
}
