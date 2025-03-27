<?php

namespace App\Http\Controllers\Public\Misc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\NoSQLInjection;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
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
}
