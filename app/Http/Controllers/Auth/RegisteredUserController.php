<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Taxa;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:45', 'regex:/^[a-zA-Z0-9 ]*$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'contacto' => ['nullable', 'string', 'max:45', 'regex:/^\+?[0-9]*$/'],
            'salario' => ['nullable', 'numeric', 'min:0'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'contacto' => $request->contacto,
            'salario' => $request->salario,
        ]);

        event(new Registered($user));

        return response()->json([
            'success' => true,
            'message' => 'User criado com sucesso!',
            'data' => $user
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {

        $request->validate([
            'name' => ['required', 'string', 'max:45', 'regex:/^[a-zA-Z0-9 ]*$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'contacto' => ['nullable', 'string', 'max:45', 'regex:/^\+?[0-9]*$/'],
            'salario' => ['nullable', 'numeric', 'min:0'],
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->contacto = $request->contacto;
        $user->salario = $request->salario;

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Utilizador atualizado com sucesso!',
            'data' => $user
        ]);
    }
}
