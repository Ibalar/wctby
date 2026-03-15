<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Список адресов пользователя
     */
    public function index()
    {
        $addresses = Auth::user()->addresses()->orderBy('is_default', 'desc')->get();

        return view('profile.addresses', compact('addresses'));
    }

    /**
     * Создать новый адрес
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:shipping,billing'],
            'city' => ['required', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
            'house' => ['nullable', 'string', 'max:50'],
            'apartment' => ['nullable', 'string', 'max:50'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $address = Auth::user()->addresses()->create($validated);

        if ($request->is_default || Auth::user()->addresses()->count() === 1) {
            $address->setAsDefault();
        }

        return back()->with('success', 'Адрес добавлен');
    }

    /**
     * Обновить адрес
     */
    public function update(Request $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'type' => ['required', 'in:shipping,billing'],
            'city' => ['required', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
            'house' => ['nullable', 'string', 'max:50'],
            'apartment' => ['nullable', 'string', 'max:50'],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ]);

        $address->update($validated);

        return back()->with('success', 'Адрес обновлён');
    }

    /**
     * Удалить адрес
     */
    public function destroy(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $newDefault = Auth::user()->addresses()->first();
            if ($newDefault) {
                $newDefault->setAsDefault();
            }
        }

        return back()->with('success', 'Адрес удалён');
    }

    /**
     * Установить адрес как основной
     */
    public function setDefault(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $address->setAsDefault();

        return back()->with('success', 'Адрес установлен как основной');
    }
}
