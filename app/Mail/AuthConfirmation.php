<?php

namespace App\Mail;

use App\Helpers\Mail\Mailable;

class AuthConfirmation extends Mailable {
    protected static string $view = "emails.auth.confirmation";
}