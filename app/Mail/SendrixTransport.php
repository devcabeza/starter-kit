<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\RawMessage;

class SendrixTransport implements TransportInterface
{
    private readonly string $key;

    private readonly string $baseUrl;

    public function __construct(
        ?string $key = null,
        string $baseUrl = 'https://sendrix.alejandrocabeza.dev',
        ?string $apiKey = null,
        ?string $projectId = null,
    ) {
        $this->key = (string) ($key ?? $apiKey ?? '');
        $this->baseUrl = $baseUrl;
    }

    public function send(RawMessage $message, ?Envelope $envelope = null): ?SentMessage
    {
        $email = $message instanceof Email
            ? $message
            : new Email;

        $html = $email->getHtmlBody() ?? $email->getTextBody() ?? '';

        $toAddresses = array_map(fn (Address $address) => $address->getAddress(), $email->getTo());
        $ccAddresses = array_map(fn (Address $address) => $address->getAddress(), $email->getCc());
        $bccAddresses = array_map(fn (Address $address) => $address->getAddress(), $email->getBcc());

        $payload = [
            'to' => $toAddresses[0] ?? '',
            'subject' => $email->getSubject() ?? '',
            'html' => $html,
        ];

        $from = $email->getFrom();
        if ($from === []) {
            $fromAddress = (string) (config('mail.from.address') ?? 'hello@example.com');
            $fromName = (string) (config('mail.from.name') ?? '');
            $email->from(new Address($fromAddress, $fromName));
            $from = $email->getFrom();
        }

        if (! empty($from[0]->getName())) {
            $payload['from_name'] = $from[0]->getName();
        }

        $replyTo = $email->getReplyTo();
        if ($replyTo !== []) {
            $payload['reply_to'] = $replyTo[0]->getAddress();
        }

        if ($ccAddresses !== []) {
            $payload['cc'] = $ccAddresses;
        }

        if ($bccAddresses !== []) {
            $payload['bcc'] = $bccAddresses;
        }

        $url = rtrim($this->baseUrl, '/').'/api/v1/send';

        $response = Http::withToken($this->key)
            ->asJson()
            ->acceptJson()
            ->timeout(10)
            ->post($url, $payload);

        if ($response->failed()) {
            $error = $response->json('error') ?? $response->json('message') ?? $response->body();
            throw new \RuntimeException("Sendrix error: {$error}", $response->status());
        }

        $sentEnvelope = $envelope ?? new Envelope(
            sender: $from !== [] ? $from[0] : new Address('sender@example.com'),
            recipients: $email->getTo(),
        );

        $sentMessage = new SentMessage($message, $sentEnvelope);

        if ($response->successful()) {
            $sendrixMessageId = $response->json('id');
            if (! empty($sendrixMessageId)) {
                $sentMessage->setMessageId((string) $sendrixMessageId);
            }
        }

        return $sentMessage;
    }

    public function __toString(): string
    {
        return 'sendrix';
    }
}
