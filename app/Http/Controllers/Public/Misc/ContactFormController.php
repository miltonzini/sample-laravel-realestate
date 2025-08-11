<?php

namespace App\Http\Controllers\Public\Misc;

use Illuminate\Http\Request;
use App\Rules\NoSQLInjection;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class ContactFormController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'website' => 'nullable|max:0',
            'full-name' => ['required', 'string', new NoSQLInjection],
            'telephone' => 'required|regex:/^[0-9\s\(\)-]+$/',
            'email' => 'nullable|email',
            'message' => ['required', 'min:20', 'max:2000', new NoSQLInjection],
            'g-recaptcha-response' => 'required',
        ]);

        $captcha = $request->input('g-recaptcha-response');
        $secretKey = env('RECAPTCHA_SECRET_KEY');
        $ip = $request->ip();
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha&remoteip=$ip");
        $attributes = json_decode($response, true);

        if (!$attributes['success']) {
            return back()->withErrors(['error' => 'Verificar captcha']);
        }

        if (!empty($request->input('website'))) {
            return back()->withErrors(['error' => 'Formulario detenido debido a posible bot.']);
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'tls');
            $mail->Port = env('MAIL_PORT', 587);
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress('info@mail.com.ar');
            $mail->isHTML(true);
            $mail->Subject = 'Nuevo mensaje desde el formulario de contacto';
            
            $mail->Body = view('emails.contactFormMessage', [
                'fullName' => $request->input('full-name'),
                'telephone' => $request->input('telephone'),
                'email' => $request->input('email'),
                'message' => $request->input('message'),
            ])->render();

            $mail->AltBody = strip_tags($mail->Body);

            $mail->send();

            return back()->with('message', 'Mensaje enviado correctamente.');

        } catch (Exception $e) {
            return back()->withErrors(['error' => "Mailer Error: {$mail->ErrorInfo}"]);
        }
    }

    public function propertyDetailsInfoSubmit(Request $request, $id)
    {
        $messages = [
            'website.max' => 'Campo no válido',
            'full-name.required' => 'El nombre es obligatorio',
            'full-name.string' => 'El nombre debe ser texto',
            'telephone.required' => 'El teléfono es obligatorio',
            'telephone.regex' => 'El formato del teléfono no es válido',
            'email.email' => 'El formato del email no es válido',
            'message.required' => 'El mensaje es obligatorio',
            'message.min' => 'El mensaje debe tener al menos 20 caracteres',
            'message.max' => 'El mensaje no puede superar los 2000 caracteres',
            'g-recaptcha-response.required' => 'Por favor, complete el captcha'
        ];
        
        $validations = $request->validate([
            'website' => 'nullable|max:0',
            'full-name' => ['required', 'string', new NoSQLInjection],
            'telephone' => 'required|regex:/^[0-9\s\(\)-]+$/',
            'email' => 'nullable|email',
            'message' => ['required', 'min:20', 'max:2000', new NoSQLInjection],
            'g-recaptcha-response' => 'required',
        ], $messages);

        $captcha = $request->input('g-recaptcha-response');
        $secretKey = env('RECAPTCHA_SECRET_KEY');
        $ip = $request->ip();
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha&remoteip=$ip");
        $attributes = json_decode($response, true);

        if (!$attributes['success']) {
            return back()->withErrors(['error' => 'Verificar captcha']);
        }

        if (!empty($request->input('website'))) {
            return back()->withErrors(['error' => 'Formulario detenido debido a posible bot.']);
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'tls');
            $mail->Port = env('MAIL_PORT', 587);
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress('info@audacity.com.ar');
            $mail->isHTML(true);
            $mail->Subject = 'Nueva consulta sobre una propiedad';
            
            $mail->Body = view('emails.propertyDetailsMessage', [
                'propertyId' => $request->input('property-id'),
                'propertyTitle' => $request->input('property-title'),
                'propertyUrl' => $request->input('property-url'),
                'fullName' => $request->input('full-name'),
                'telephone' => $request->input('telephone'),
                'email' => $request->input('email'),
                'message' => $request->input('message'),
            ])->render();

            $mail->AltBody = strip_tags($mail->Body);

            $mail->send();

            return response()->json([
                'success' => true, 
                'message' => 'Se envió la consulta con éxito'
            ]);

        } catch (Exception $e) {
            return back()->withErrors(['error' => "Mailer Error: {$mail->ErrorInfo}"]);
        }
    }

    public function developmenrtDetailsInfoSubmit(Request $request, $id)
    {
        $messages = [
            'website.max' => 'Campo no válido',
            'full-name.required' => 'El nombre es obligatorio',
            'full-name.string' => 'El nombre debe ser texto',
            'telephone.required' => 'El teléfono es obligatorio',
            'telephone.regex' => 'El formato del teléfono no es válido',
            'email.email' => 'El formato del email no es válido',
            'message.required' => 'El mensaje es obligatorio',
            'message.min' => 'El mensaje debe tener al menos 20 caracteres',
            'message.max' => 'El mensaje no puede superar los 2000 caracteres',
            'g-recaptcha-response.required' => 'Por favor, complete el captcha'
        ];
        
        $validations = $request->validate([
            'website' => 'nullable|max:0',
            'full-name' => ['required', 'string', new NoSQLInjection],
            'telephone' => 'required|regex:/^[0-9\s\(\)-]+$/',
            'email' => 'nullable|email',
            'message' => ['required', 'min:20', 'max:2000', new NoSQLInjection],
            'g-recaptcha-response' => 'required',
        ], $messages);

        $captcha = $request->input('g-recaptcha-response');
        $secretKey = env('RECAPTCHA_SECRET_KEY');
        $ip = $request->ip();
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha&remoteip=$ip");
        $attributes = json_decode($response, true);

        if (!$attributes['success']) {
            return back()->withErrors(['error' => 'Verificar captcha']);
        }

        if (!empty($request->input('website'))) {
            return back()->withErrors(['error' => 'Formulario detenido debido a posible bot.']);
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'tls');
            $mail->Port = env('MAIL_PORT', 587);
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress('info@audacity.com.ar');
            $mail->isHTML(true);
            $mail->Subject = 'Nueva consulta sobre un emprendimiento';
            
            $mail->Body = view('emails.developmentDetailsMessage', [
                'developmentId' => $request->input('development-id'),
                'developmentTitle' => $request->input('development-title'),
                'developmentUrl' => $request->input('development-url'),
                'fullName' => $request->input('full-name'),
                'telephone' => $request->input('telephone'),
                'email' => $request->input('email'),
                'message' => $request->input('message'),
            ])->render();

            $mail->AltBody = strip_tags($mail->Body);

            $mail->send();

            return response()->json([
                'success' => true, 
                'message' => 'Se envió la consulta con éxito'
            ]);

        } catch (Exception $e) {
            return back()->withErrors(['error' => "Mailer Error: {$mail->ErrorInfo}"]);
        }
    }

    public function lotDetailsInfoSubmit(Request $request, $id)
    {
        $messages = [
            'website.max' => 'Campo no válido',
            'full-name.required' => 'El nombre es obligatorio',
            'full-name.string' => 'El nombre debe ser texto',
            'telephone.required' => 'El teléfono es obligatorio',
            'telephone.regex' => 'El formato del teléfono no es válido',
            'email.email' => 'El formato del email no es válido',
            'message.required' => 'El mensaje es obligatorio',
            'message.min' => 'El mensaje debe tener al menos 20 caracteres',
            'message.max' => 'El mensaje no puede superar los 2000 caracteres',
            'g-recaptcha-response.required' => 'Por favor, complete el captcha'
        ];
        
        $validations = $request->validate([
            'website' => 'nullable|max:0',
            'full-name' => ['required', 'string', new NoSQLInjection],
            'telephone' => 'required|regex:/^[0-9\s\(\)-]+$/',
            'email' => 'nullable|email',
            'message' => ['required', 'min:20', 'max:2000', new NoSQLInjection],
            'g-recaptcha-response' => 'required',
        ], $messages);

        $captcha = $request->input('g-recaptcha-response');
        $secretKey = env('RECAPTCHA_SECRET_KEY');
        $ip = $request->ip();
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha&remoteip=$ip");
        $attributes = json_decode($response, true);

        if (!$attributes['success']) {
            return back()->withErrors(['error' => 'Verificar captcha']);
        }

        if (!empty($request->input('website'))) {
            return back()->withErrors(['error' => 'Formulario detenido debido a posible bot.']);
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'tls');
            $mail->Port = env('MAIL_PORT', 587);
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress('info@audacity.com.ar');
            $mail->isHTML(true);
            $mail->Subject = 'Nueva consulta sobre un lote/terreno';
            
            $mail->Body = view('emails.lotDetailsMessage', [
                'lotId' => $request->input('lot-id'),
                'lotTitle' => $request->input('lot-title'),
                'lotUrl' => $request->input('lot-url'),
                'fullName' => $request->input('full-name'),
                'telephone' => $request->input('telephone'),
                'email' => $request->input('email'),
                'message' => $request->input('message'),
            ])->render();

            $mail->AltBody = strip_tags($mail->Body);

            $mail->send();

            return response()->json([
                'success' => true, 
                'message' => 'Se envió la consulta con éxito'
            ]);

        } catch (Exception $e) {
            return back()->withErrors(['error' => "Mailer Error: {$mail->ErrorInfo}"]);
        }
    }
}
