<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\SocialAccountService;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct(
        protected SocialAccountService $socialAccounts
    ) {}

    public function index()
    {
        $user = Auth::user()->load([
            'orders' => fn ($q) => $q->latest()->withCount('items')->limit(5),
        ]);

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
        $currentEmail = $user->email;

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

        $emailChanged = isset($validated['email']) && $validated['email'] !== $currentEmail;
        $user->update($validated);

        if ($emailChanged) {
            $user->forceFill(['email_verified_at' => null])->save();
        }

        $verificationNotificationSent = false;

        if ($emailChanged && method_exists($user, 'sendEmailVerificationNotification')) {
            try {
                $user->sendEmailVerificationNotification();
                $verificationNotificationSent = true;
            } catch (Throwable) {
                $verificationNotificationSent = false;
            }
        }

        if ($emailChanged) {
            if ($verificationNotificationSent) {
                return redirect()
                    ->route('profile.index')
                    ->with('success', 'Профиль обновлён. Подтвердите новый email по ссылке в письме.');
            }

            return redirect()
                ->route('profile.index')
                ->with('error', 'Профиль обновлён, но письмо подтверждения отправить не удалось. Повторите отправку из баннера вверху страницы.');
        }

        return redirect()->route('profile.index')->with('success', 'Профиль обновлён');
    }

    public function orders(Request $request)
    {
        $user = Auth::user();
        $baseQuery = $user->orders();
        $query = $user->orders()->withCount('items')->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);
        $totalOrders = $baseQuery->count();
        $totalSpent = $user->orders()->where('status', 'completed')->sum('total');
        $statusCounts = $user->orders()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return view('profile.orders', compact('orders', 'user', 'totalOrders', 'totalSpent', 'statusCounts'));
    }

    public function orderShow(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();
        $order->load('items');
        $totalOrders = $user->orders()->count();
        $totalSpent = $user->orders()->where('status', 'completed')->sum('total');

        return view('profile.order', compact('order', 'user', 'totalOrders', 'totalSpent'));
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
            'password' => Hash::make($request->password),
        ]);
        Auth::logoutOtherDevices($request->password);

        return back()->with('success', 'Пароль изменён');
    }

    public function socialAccounts()
    {
        $user = Auth::user()->load('socialAccounts');

        $providerMeta = config('social_auth.providers', []);
        $providers = $this->socialAccounts->providers();
        $linkedProviders = $user->socialAccounts->pluck('provider')->toArray();
        $totalOrders = $user->orders()->count();
        $totalSpent = $user->orders()->where('status', 'completed')->sum('total');

        return view('profile.social', compact('user', 'providers', 'providerMeta', 'linkedProviders', 'totalOrders', 'totalSpent'));
    }

    public function unlinkSocial(string $provider)
    {
        if (! $this->socialAccounts->isSupportedProvider($provider)) {
            return back()->with('error', 'Неподдерживаемый провайдер авторизации');
        }

        $result = $this->socialAccounts->unlinkAccount(Auth::user(), $provider);

        if ($result === SocialAccountService::UNLINK_NOT_FOUND) {
            return back()->with('error', 'Аккаунт не найден');
        }

        if ($result === SocialAccountService::UNLINK_LAST_METHOD) {
            return back()->with('error', 'Невозможно отвязать последний способ входа.');
        }

        return back()->with('success', 'Аккаунт '.ucfirst($provider).' отвязан');
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
