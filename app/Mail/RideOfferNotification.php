<?php

namespace App\Mail;

use App\Models\RideRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RideOfferNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $rideRequest;

    public function __construct(RideRequest $rideRequest)
    {
        $this->rideRequest = $rideRequest;
    }

    public function build()
    {
        return $this->markdown('emails.ride-offer-notification')
            ->subject('Ride Offer for Friday Lunch');
    }
}
