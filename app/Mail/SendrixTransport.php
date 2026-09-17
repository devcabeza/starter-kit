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

        $to = collect($email->getTo())
            ->map(fn (Address $address) => $address->getAddress())
            ->values()
            ->all();

        $cc = collect($email->getCc())
            ->map(fn (Address $address) => $address->getAddress())
            ->values()
            ->all();

        $bcc = collect($email->getBcc())
            ->map(fn (Address $address) => $address->getAddress())
            ->values()
            ->all();

        $from = $email->getFrom();
        $replyTo = $email->getReplyTo();

        $payload = [
            'to' => $to,
            'subject' => $email->getSubject() ?? '',
            'html' => $html,
            'from_name' => $from !== [] ? $from[0]->getName() : null,
            'reply_to' => $replyTo !== [] ? $replyTo[0]->getAddress() : null,
            'cc' => $cc,
            'bcc' => $bcc,
        ];

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'X-Project-ID' => $this->projectId,
            'Content-Type' => 'application/json',
        ])->timeout(10)->post("{$this->baseUrl}/api/v1/send", $payload);

        if ($response->failed()) {
            $error = $response->json('error', $response->body());
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
