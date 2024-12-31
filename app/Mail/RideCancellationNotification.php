<?php

namespace App\Mail;

use App\Models\RideRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RideCancellationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $rideRequest;
    public $cancelledByUser;

    public function __construct(RideRequest $rideRequest, User $cancelledByUser)
    {
        $this->rideRequest = $rideRequest;
        $this->cancelledByUser = $cancelledByUser;
    }

    public function build()
    {
        return $this->markdown('emails.ride-cancellation-notification')
            ->subject('Ride Cancelled for Friday Lunch');
    }
}
