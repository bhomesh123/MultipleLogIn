<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
// use Laravel\Socialite\Facades\Socialite;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;
use Exception;

class LoginWithShopifyController extends Controller
{
    public function redirectToShopify()
    {
        $shopifyAuthUrl = 'https://trailstore-17.myshopify.com/admin/oauth/authorize'; // Replace SHOP_NAME with your Shopify store name
        $queryParams = [
            'client_id' => env('SHOPIFY_API_KEY'),
            'scope' => 'read_products,write_products', // Adjust scopes based on your needs
            'redirect_uri' => env('SHOPIFY_REDIRECT_URI'),
            'state' => csrf_token(),
        ];

        return Redirect::to($shopifyAuthUrl . '?' . http_build_query($queryParams));
    }

    public function handleShopifyCallback(Request $request)
    {
        dd( $request);
        // Verify the state parameter to prevent CSRF attacks
        if ($request->input('state') !== csrf_token()) {
            abort(401, 'Invalid state parameter');
        }
        $response = Http::post('https://trailstore-17.myshopify.com/admin/oauth/access_token', [
            'client_id' => env('SHOPIFY_API_KEY'),
            'client_secret' => env('SHOPIFY_API_SECRET'),
            'code' => $request->input('code'),
        ]);

        $accessToken = $response->json()['access_token'];

        // Use $accessToken to make requests to the Shopify API
              // Retrieve user information from the database based on Shopify credentials
              $user = User::where('shopify_access_token', $accessToken)->first();

              if ($user) {
                  // User exists, log them in
                  Auth::login($user);
                  return redirect()->intended('dashboard');
              } else {
                  // User doesn't exist, create a new account or show an error message
                  // Example: return redirect()->route('register')->with('error', 'Shopify account not registered.');
                  return 'Shopify account not registered.';
              }

        return 'Successfully authenticated with Shopify!';
    }
}

