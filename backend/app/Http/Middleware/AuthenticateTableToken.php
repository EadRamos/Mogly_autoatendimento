<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthenticateTableToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['mensagem' => 'Token de mesa não fornecido.'], 401);
        }

        // Tente decodificar o token (assumindo que o frontend enviará um JSON com 'data' e 'signature')
        try {
            $json = json_decode($token, true);
            $dados = $json['data'] ?? null;
            $assinatura = $json['assinatura'] ?? null;

            if (!$dados || !$assinatura || !is_array($dados) || !isset($dados['expira_em'])) {
                return response()->json(['mensagem' => 'Token de mesa inválido.'], 401);
            }

            // Verifique se o token expirou
            if ($dados['expires_at'] < Carbon::now()->timestamp) {
                return response()->json(['mensagem' => 'Token de mesa expirado.'], 401);
            }

            // Verifique a assinatura
            //$expectedSignature = Hash::make(json_encode($data) . env('TABLE_AUTH_SECRET'));
            if (!Hash::check(json_encode($dados) . env('TABLE_AUTH_SECRET'), $assinatura)) {
                return response()->json(['mensagem' => 'Assinatura do token de mesa inválida.'], 401);
            }

            // Adicione as informações da mesa à requisição
            $request->attributes->add(['dados_mesa' => $dados]);

            return $next($request);

        } catch (\Exception $e) {
            return response()->json(['mensagem' => 'Erro ao processar o token de mesa.', 'erro' => $e], 401);
        }
    }
}