<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        // Genera la URL absoluta utilizando el APP_URL definido en tu archivo .env
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Recuperación de Contraseña - Transparencia CONALEP')
            ->greeting('¡Hola, ' . $notifiable->name . '!')
            ->line('Recibimos una solicitud para restablecer la contraseña de tu cuenta en el Repositorio de Transparencia CONALEP.')
            ->action('Restablecer Contraseña', $url)
            ->line('Este enlace de recuperación expirará en 60 minutos.')
            ->line('Si estás en un entorno de pruebas local y tu correo bloquea el botón, copia y pega este enlace en una ventana de incógnito:')
            ->line($url)
            ->line('Si tú no solicitaste este cambio, puedes ignorar este correo de forma segura.')
            ->salutation('Atentamente, Administración de Transparencia CONALEP.');
    }
}