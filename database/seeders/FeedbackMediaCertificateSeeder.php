<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Event;
use App\Models\Feedback;
use App\Models\Media;
use App\Models\Registration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FeedbackMediaCertificateSeeder extends Seeder
{
    public function run(): void
    {
        $events = Event::all();

        foreach ($events as $event) {
            // Feedbacks
            foreach ($event->registrations()->with('user')->limit(5)->get() as $reg) {
                Feedback::updateOrCreate(
                    ['event_id' => $event->id, 'user_id' => $reg->user_id],
                    ['rating' => random_int(3, 5), 'comment' => 'Great event!']
                );
            }

            // Media
            Media::firstOrCreate(
                ['url' => 'https://picsum.photos/seed/'.Str::slug($event->title).'/800/600'],
                [
                    'event_id' => $event->id,
                    'uploader_id' => $event->organizer_id,
                    'type' => 'image',
                    'status' => 'approved',
                    'thumb_url' => 'https://picsum.photos/seed/'.Str::slug($event->title).'/320/180',
                ]
            );

            // Certificates for paid students
            $paidRegs = Registration::where('event_id', $event->id)->where('fee_paid', true)->limit(5)->get();
            foreach ($paidRegs as $reg) {
                Certificate::firstOrCreate(
                    ['certificate_id' => 'CERT-'.strtoupper(Str::random(8)).'-'.$reg->id],
                    [
                        'event_id' => $event->id,
                        'student_id' => $reg->user_id,
                        'pdf_url' => 'https://example.com/certificates/'.$reg->id.'.pdf',
                        'issued_on' => now(),
                        'fee_paid' => true,
                    ]
                );
            }
        }
    }
}


