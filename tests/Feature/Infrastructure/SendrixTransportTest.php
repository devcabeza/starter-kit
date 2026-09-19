<?php

declare(strict_types=1);

use App\Mail\SendrixTransport;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

it('sends an email via sendrix transport with correct payload structure', function () {
    Http::fake([
        'https://sendrix.example.com/api/v1/send' => Http::response([
            'success' => true,
            'id' => 'resend-123',
            'log_id' => '01J3XYZABCDEF0123456789',
            'timestamp' => now()->toISOString(),
        ], 200),
    ]);

    $transport = new SendrixTransport(
        apiKey: 'sk_proj_test_api_key',
        projectId: '00000000-0000-0000-0000-000000000000',
        baseUrl: 'https://sendrix.example.com',
    );

    $email = (new Email)
        ->from(new Address('hello@laravertex.com', 'Laravertex App'))
        ->to('usuario@ejemplo.com')
        ->replyTo('support@laravertex.com')
        ->subject('Tu código de acceso')
        ->html('<p>Tu código es 123456</p>');

    $sentMessage = $transport->send($email);

    expect($sentMessage)->not->toBeNull();

    Http::assertSent(function ($request) {
        $data = $request->data();

        return $request->url() === 'https://sendrix.example.com/api/v1/send'
            && $request->header('Authorization')[0] === 'Bearer sk_proj_test_api_key'
            && $request->header('X-Project-ID')[0] === '00000000-0000-0000-0000-000000000000'
            && $data['to'] === 'usuario@ejemplo.com'
            && is_string($data['to'])
            && $data['subject'] === 'Tu código de acceso'
            && $data['from_name'] === 'Laravertex App'
            && $data['reply_to'] === 'support@laravertex.com'
            && ! isset($data['cc'])
            && ! isset($data['bcc']);
    });
});

it('throws a runtime exception when sendrix returns an error status', function () {
    Http::fake([
        'https://sendrix.example.com/api/v1/send' => Http::response([
            'error' => 'invalid_api_key',
            'message' => 'Project API key is invalid or project is inactive.',
        ], 401),
    ]);

    $transport = new SendrixTransport(
        apiKey: 'sk_proj_bad_key',
        projectId: '00000000-0000-0000-0000-000000000000',
        baseUrl: 'https://sendrix.example.com',
    );

    $email = (new Email)
        ->to('usuario@ejemplo.com')
        ->subject('Test')
        ->text('Mensaje');

    expect(fn () => $transport->send($email))
        ->toThrow(RuntimeException::class, 'Sendrix error');
});
