<?php
namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Jobs\SendVerificationEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        
        SendVerificationEmail::dispatch($user);

        return response()->json(['message' => 'Foydalanuvchi ro‘yxatdan o‘tdi. Emailingizni tasdiqlang.']);
    }

    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Xatolik: noto‘g‘ri havola.'], 403);
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return response()->json(['message' => 'Email muvaffaqiyatli tasdiqlandi.']);
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email allaqachon tasdiqlangan.'], 400);
        }

        SendVerificationEmail::dispatch($request->user());

        return response()->json(['message' => 'Tasdiqlash linki qayta yuborildi.']);
    }




     public function login(LoginRequest $request)
{
    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Email yoki parol noto‘g‘ri'], 401);
    }

    $user = $request->user();

    if (!$user->hasVerifiedEmail()) {
        return response()->json(['message' => 'Email tasdiqlanmagan'], 403);
    }

    
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Tizimga muvaffaqiyatli kirdingiz',
        'token' => $token,
        'user' => $user
    ]);
}
public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Tizimdan chiqdingiz']);
}


}