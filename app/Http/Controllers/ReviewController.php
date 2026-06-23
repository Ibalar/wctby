<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $review = Review::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'product_id' => $validated['product_id'],
            ],
            [
                'rating' => $validated['rating'],
                'title' => $validated['title'] ?? null,
                'body' => $validated['body'],
                'is_approved' => false,
            ],
        );

        Log::info('[ReviewController] Review saved', [
            'review_id' => $review->id,
            'user_id' => $request->user()->id,
            'product_id' => $validated['product_id'],
            'was_recently_created' => $review->wasRecentlyCreated,
        ]);

        return response()->json([
            'message' => $review->wasRecentlyCreated
                ? 'Отзыв отправлен на модерацию'
                : 'Отзыв обновлён',
            'review' => $review,
        ]);
    }
}
