<?php

namespace App\Mail;

use App\Helpers\Mail\Mailable;

class PaymentConfirmation extends Mailable {
    protected static string $view = "emails.payment.confirmation";
}