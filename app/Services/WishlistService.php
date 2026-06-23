<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Str;

class WishlistService
{
    public function mergeGuestWishlist(User $user, string $sessionToken): void
    {
        $guestItems = Wishlist::where('session_token', $sessionToken)->get();

        foreach ($guestItems as $guestItem) {
            Wishlist::firstOrCreate([
                'user_id' => $user->id,
                'product_id' => $guestItem->product_id,
            ]);
        }

        Wishlist::where('session_token', $sessionToken)->delete();
    }
}
