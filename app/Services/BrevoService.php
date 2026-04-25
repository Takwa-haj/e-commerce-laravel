<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoService
{
    protected $apiKey;
    protected $apiUrl = 'https://api.brevo.com/v3/smtp/email';

    public function __construct()
    {
        $this->apiKey = config('services.brevo.api_key');
    }

    /**
     * Send email using Brevo template
     */
    public function sendTemplateEmail($to, $templateId, $params = [], $attachments = [], $cc = null, $bcc = null): array
    {
        if (!$this->apiKey) {
            Log::error('Brevo API key missing');
            return ['success' => false, 'message' => 'Email configuration incomplete'];
        }

        $payload = [
            'templateId' => (int) $templateId,
            'to' => [$to],
            'params' => $params,
        ];

        if (!empty($attachments)) {
            $payload['attachment'] = $attachments;
        }

        if ($cc) {
            $payload['cc'] = $cc;
        }

        if ($bcc) {
            $payload['bcc'] = $bcc;
        }

        try {
            $response = Http::withHeaders([
                'api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->apiUrl, $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Brevo email sent successfully', [
                    'to' => $to['email'],
                    'templateId' => $templateId,
                    'messageId' => $responseData['messageId'] ?? null
                ]);
                return [
                    'success' => true,
                    'data' => $responseData,
                    'messageId' => $responseData['messageId'] ?? null
                ];
            } else {
                Log::error('Brevo API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return [
                    'success' => false,
                    'message' => $response->json()['message'] ?? 'Unknown error'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Brevo API exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Email service connection error'];
        }
    }

    /**
     * Send custom HTML email (without template)
     */
    public function sendCustomEmail($to, $subject, $htmlContent, $attachments = [], $cc = null, $bcc = null): array
    {
        if (!$this->apiKey) {
            Log::error('Brevo API key missing');
            return ['success' => false, 'message' => 'Email configuration incomplete'];
        }

        $payload = [
            'sender' => [
                'name' => config('mail.from.name'),
                'email' => config('mail.from.address'),
            ],
            'to' => [$to],
            'subject' => $subject,
            'htmlContent' => $htmlContent,
        ];

        if (!empty($attachments)) {
            $payload['attachment'] = $attachments;
        }

        if ($cc) {
            $payload['cc'] = $cc;
        }

        if ($bcc) {
            $payload['bcc'] = $bcc;
        }

        try {
            $response = Http::withHeaders([
                'api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->apiUrl, $payload);

            if ($response->successful()) {
                $responseData = $response->json();
                Log::info('Brevo custom email sent successfully', [
                    'to' => $to['email'],
                    'subject' => $subject,
                    'messageId' => $responseData['messageId'] ?? null
                ]);
                return [
                    'success' => true,
                    'data' => $responseData,
                    'messageId' => $responseData['messageId'] ?? null
                ];
            } else {
                Log::error('Brevo API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return [
                    'success' => false,
                    'message' => $response->json()['message'] ?? 'Unknown error'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Brevo API exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Email service connection error'];
        }
    }

    /**
     * Prepare file attachment for Brevo API
     */
    public function prepareAttachment($filePath, $fileName): ?array
    {
        if (!file_exists($filePath)) {
            Log::warning("Attachment not found: " . $filePath);
            return null;
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            Log::warning("Could not read attachment: " . $filePath);
            return null;
        }

        return [
            'name' => $fileName,
            'content' => base64_encode($content),
        ];
    }

    /**
     * Prepare attachment from raw content
     */
    public function prepareAttachmentFromContent($content, $fileName): array
    {
        return [
            'name' => $fileName,
            'content' => base64_encode($content),
        ];
    }
}
