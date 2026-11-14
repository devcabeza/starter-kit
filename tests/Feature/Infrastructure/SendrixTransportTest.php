<?php

declare(strict_types=1);

use App\Mail\SendrixTransport;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

it('sends an email via sendrix transport with correct payload structure', function () {
    Http::fake([
        'https://sendrix.alejandrocabeza.dev/api/v1/send' => Http::response([
            'success' => true,
            'id' => 'resend-123',
            'log_id' => '01J3XYZABCDEF0123456789',
            'timestamp' => now()->toISOString(),
        ], 200),
    ]);

    $transport = new SendrixTransport(
        key: 'sk_proj_test_api_key',
        baseUrl: 'https://sendrix.alejandrocabeza.dev',
    );

    $email = (new Email)
        ->from(new Address('hello@laravertex.com', 'Laravertex App'))
        ->to('usuario@ejemplo.com')
        ->replyTo('support@laravertex.com')
        ->subject('Tu código de acceso')
        ->html('<p>Tu código es 123456</p>');

    $sentMessage = $transport->send($email);

    expect($sentMessage)->not->toBeNull()
        ->and($sentMessage->getMessageId())->toBe('resend-123');

    Http::assertSent(function ($request) {
        $data = $request->data();

        return $request->url() === 'https://sendrix.alejandrocabeza.dev/api/v1/send'
            && $request->header('Authorization')[0] === 'Bearer sk_proj_test_api_key'
            && ! $request->hasHeader('X-Project-ID')
            && $data['to'] === 'usuario@ejemplo.com'
            && is_string($data['to'])
            && $data['subject'] === 'Tu código de acceso'
            && $data['from_name'] === 'Laravertex App'
            && $data['reply_to'] === 'support@laravertex.com'
            && ! isset($data['cc'])
            && ! isset($data['bcc']);
    });
});

it('uses default base url when none is provided', function () {
    Http::fake([
        'https://sendrix.alejandrocabeza.dev/api/v1/send' => Http::response([
            'id' => 'default-url-456',
        ], 200),
    ]);

    $transport = new SendrixTransport(
        key: 'sk_proj_default_test_key',
    );

    $email = (new Email)
        ->to('usuario@ejemplo.com')
        ->subject('Asunto')
        ->html('<h1>Hola</h1>');

    $sentMessage = $transport->send($email);

    expect($sentMessage)->not->toBeNull()
        ->and($sentMessage->getMessageId())->toBe('default-url-456');

    Http::assertSent(function ($request) {
        return $request->url() === 'https://sendrix.alejandrocabeza.dev/api/v1/send'
            && $request->header('Authorization')[0] === 'Bearer sk_proj_default_test_key';
    });
});

it('throws a runtime exception when sendrix returns an error status', function () {
    Http::fake([
        'https://sendrix.alejandrocabeza.dev/api/v1/send' => Http::response([
            'error' => 'invalid_api_key',
            'message' => 'Project API key is invalid or project is inactive.',
        ], 401),
    ]);

    $transport = new SendrixTransport(
        key: 'sk_proj_bad_key',
        baseUrl: 'https://sendrix.alejandrocabeza.dev',
    );

    $email = (new Email)
        ->to('usuario@ejemplo.com')
        ->subject('Test')
        ->text('Mensaje');

    expect(fn () => $transport->send($email))
        ->toThrow(RuntimeException::class, 'Sendrix error');
});

it('delivers mail through laravel mail facade using configured sendrix transport', function () {
    config()->set('services.sendrix.key', 'sk_live_configured_token');
    config()->set('services.sendrix.base_url', 'https://sendrix.alejandrocabeza.dev');
    config()->set('mail.default', 'sendrix');

    Http::fake([
        'https://sendrix.alejandrocabeza.dev/api/v1/send' => Http::response([
            'id' => 'sendrix_msg_789',
        ], 200),
    ]);

    Mail::html('<h1>¡Hola!</h1><p>Mensaje procesado con éxito.</p>', function ($message) {
        $message->to('usuario@ejemplo.com')
            ->subject('Notificación de mi App');
    });

    Http::assertSent(function ($request) {
        return $request->url() === 'https://sendrix.alejandrocabeza.dev/api/v1/send'
            && $request->header('Authorization')[0] === 'Bearer sk_live_configured_token'
            && ! $request->hasHeader('X-Project-ID')
            && $request['to'] === 'usuario@ejemplo.com'
            && $request['subject'] === 'Notificación de mi App'
            && $request['html'] === '<h1>¡Hola!</h1><p>Mensaje procesado con éxito.</p>';
    });
});
