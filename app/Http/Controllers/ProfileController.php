<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load(['orders' => fn ($q) => $q->latest()->limit(5)]);

        $lastOrders = $user->orders;
        $totalOrders = $user->orders()->count();
        $totalSpent = $user->orders()->where('status', 'completed')->sum('total');

        return view('profile.index', compact('user', 'lastOrders', 'totalOrders', 'totalSpent'));
    }

    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone,'.$user->id],
            'birthday' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return redirect()->route('profile.index')->with('success', 'Профиль обновлён');
    }

    public function orders(Request $request)
    {
        $query = Auth::user()->orders()->with('items')->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);

        return view('profile.orders', compact('orders'));
    }

    public function orderShow(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items');

        return view('profile.order', compact('order'));
    }

    public function security()
    {
        return view('profile.security');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return back()->with('success', 'Пароль изменён');
    }

    public function socialAccounts()
    {
        $user = Auth::user()->load('socialAccounts');

        $providers = ['google', 'vkontakte', 'telegram', 'yandex'];
        $linkedProviders = $user->socialAccounts->pluck('provider')->toArray();

        return view('profile.social', compact('user', 'providers', 'linkedProviders'));
    }

    public function unlinkSocial(string $provider)
    {
        $user = Auth::user();

        if (! $user->password) {
            return back()->with('error', 'Невозможно отвязать последний способ входа. Сначала задайте пароль.');
        }

        $deleted = $user->socialAccounts()->where('provider', $provider)->delete();

        if ($deleted) {
            return back()->with('success', 'Аккаунт '.ucfirst($provider).' отвязан');
        }

        return back()->with('error', 'Аккаунт не найден');
    }

    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return back()->with('success', 'Аватар удалён');
    }
}
