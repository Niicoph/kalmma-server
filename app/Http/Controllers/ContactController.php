<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactoMailable;
use Exception;

class ContactController extends Controller
{
    public function enviarFormulario(Request $request)
    {
        // Validar los datos del formulario
        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'email' => 'required|email',
                'asunto' => 'required|string|max:200',
                'mensaje' => 'required|string|max:2000',
            ]);

            Mail::to('niicoph@gmail.com')
                ->send(new ContactoMailable($validatedData));

            return response()->json(['message' => 'Gracias por contactarnos. Te responderemos pronto.'], 200);
        } catch (Exception $e) {
            return response()->json(['error', $e], 200);
        }
    }
}
