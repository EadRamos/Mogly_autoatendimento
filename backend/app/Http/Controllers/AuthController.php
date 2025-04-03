<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use app\Models\User;

class AuthController extends Controller
{
    public function registrar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['erro' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'tipo_token' => 'Bearer'], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['erro' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['mensagem' => 'Credenciais inválidas'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'tipo_token' => 'Bearer'], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['mensagem' => 'Logout realizado com sucesso'], 200);
    }

    public function autenticarMesa(int $restaurante, int $mesa)
    {
        $expiraEm = Carbon::now()->addMinutes(60)->timestamp; // Expira em 60 minutos
        $dados = [
            'restaurante_id' => $restaurante,
            'mesa_id' => $mesa,
            'expira_em' => $expiraEm,
        ];

        // Gere um hash usando a chave secreta e os dados
        $assinatura = Hash::make(json_encode($dados) . env('TABLE_AUTH_SECRET', '1234'));

        // Retorne os dados e a assinatura
        return response()->json([
            'dados' => $dados,
            'assinatura' => $assinatura,
            'token_type' => 'Bearer',
            'permissoes' => [],
        ],200);
    }
}