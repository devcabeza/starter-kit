<?php

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
    public function __construct(
        private readonly string $apiKey,
        private readonly string $projectId,
        private readonly string $baseUrl,
    ) {}

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
        if ($from !== [] && ! empty($from[0]->getName())) {
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

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'X-Project-ID' => $this->projectId,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->timeout(10)->post($url, $payload);

        if ($response->failed()) {
            $error = $response->json('error') ?? $response->json('message') ?? $response->body();
            throw new \RuntimeException("Sendrix error: {$error}", $response->status());
        }

        $sentEnvelope = $envelope ?? new Envelope(
            sender: $from !== [] ? $from[0] : new Address('sender@example.com'),
            recipients: $email->getTo(),
        );

        return new SentMessage($message, $sentEnvelope);
    }

    public function __toString(): string
    {
        return 'sendrix';
    }
}
