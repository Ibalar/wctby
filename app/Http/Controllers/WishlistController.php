<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlistItems = $this->getWishlistQuery($request)
            ->with(['product.media', 'product.skus' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('wishlist.index', [
            'wishlistItems' => $wishlistItems,
            'total' => $wishlistItems->total(),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $productId = $request->input('product_id');

        if ($request->user()) {
            $wishlist = Wishlist::firstOrCreate([
                'user_id' => $request->user()->id,
                'product_id' => $productId,
            ]);
        } else {
            $sessionToken = $this->getOrCreateSessionToken($request);
            $wishlist = Wishlist::firstOrCreate([
                'session_token' => $sessionToken,
                'product_id' => $productId,
            ]);
        }

        $count = $this->getWishlistCount($request);

        return response()->json([
            'message' => 'Товар добавлен в избранное',
            'count' => $count,
            'wishlist_id' => $wishlist->id,
        ]);
    }

    public function remove(Request $request, Wishlist $wishlist)
    {
        if (!$this->wishlistBelongsToUser($request, $wishlist)) {
            abort(404);
        }

        $wishlist->delete();

        $count = $this->getWishlistCount($request);

        return response()->json([
            'message' => 'Товар удалён из избранного',
            'count' => $count,
        ]);
    }

    public function count(Request $request)
    {
        $count = $this->getWishlistCount($request);

        return response()->json([
            'count' => $count,
        ]);
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $productId = $request->input('product_id');

        if ($request->user()) {
            $wishlist = Wishlist::where('user_id', $request->user()->id)
                ->where('product_id', $productId)
                ->first();
        } else {
            $sessionToken = $this->getOrCreateSessionToken($request);
            $wishlist = Wishlist::where('session_token', $sessionToken)
                ->where('product_id', $productId)
                ->first();
        }

        if ($wishlist) {
            $wishlist->delete();
            $added = false;
            $message = 'Товар удалён из избранного';
        } else {
            if ($request->user()) {
                Wishlist::create([
                    'user_id' => $request->user()->id,
                    'product_id' => $productId,
                ]);
            } else {
                $sessionToken = $this->getOrCreateSessionToken($request);
                Wishlist::create([
                    'session_token' => $sessionToken,
                    'product_id' => $productId,
                ]);
            }
            $added = true;
            $message = 'Товар добавлен в избранное';
        }

        $count = $this->getWishlistCount($request);

        return response()->json([
            'message' => $message,
            'count' => $count,
            'added' => $added,
        ]);
    }

    protected function getWishlistQuery(Request $request)
    {
        if ($request->user()) {
            return Wishlist::where('user_id', $request->user()->id);
        }

        $sessionToken = $request->session()->get('wishlist_token');
        if (!$sessionToken) {
            return Wishlist::where('id', 0);
        }

        return Wishlist::where('session_token', $sessionToken);
    }

    protected function getWishlistCount(Request $request): int
    {
        return $this->getWishlistQuery($request)->count();
    }

    protected function getOrCreateSessionToken(Request $request): string
    {
        $sessionToken = $request->session()->get('wishlist_token');

        if (!$sessionToken) {
            $sessionToken = Str::random(40);
            $request->session()->put('wishlist_token', $sessionToken);
        }

        return $sessionToken;
    }

    protected function wishlistBelongsToUser(Request $request, Wishlist $wishlist): bool
    {
        if ($request->user()) {
            return $wishlist->user_id === $request->user()->id;
        }

        $sessionToken = $request->session()->get('wishlist_token');
        return $sessionToken && $wishlist->session_token === $sessionToken;
    }
}
