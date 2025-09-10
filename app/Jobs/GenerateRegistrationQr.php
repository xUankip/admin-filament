<?php

namespace App\Jobs;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateRegistrationQr implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $registrationId)
    {
    }

    public function handle(): void
    {
        $registration = Registration::find($this->registrationId);
        if (! $registration) return;

        $payload = $registration->checkin_code;
        // Placeholder: generate a simple PNG from an external service or built-in later
        $png = base64_decode(''); // TODO: integrate QR generator (e.g., simplesoftwareio/simple-qrcode)
        // For now, just store a text file as a placeholder
        $path = 'qr/registrations/'.$registration->id.'.txt';
        Storage::disk('public')->put($path, $payload);
        $registration->update(['qr_url' => Storage::disk('public')->url($path)]);
    }
}


