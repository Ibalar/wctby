<?php

namespace App\Http\Responses;

use App\Services\CartService;
use App\Services\WishlistService;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        if ($request->user()) {
            $wishlistToken = $request->session()->get('wishlist_token');
            if ($wishlistToken) {
                app(WishlistService::class)->mergeGuestWishlist($request->user(), $wishlistToken);
                $request->session()->forget('wishlist_token');
            }

            $cartToken = $request->session()->get('cart_token');
            if ($cartToken) {
                app(CartService::class)->mergeGuestCart($request->user(), $cartToken);
                $request->session()->forget('cart_token');
            }
        }

        return redirect()->intended(route('profile.index'));
    }
}
