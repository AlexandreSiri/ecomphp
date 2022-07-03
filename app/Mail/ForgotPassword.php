<?php

namespace App\Mail;

use App\Helpers\Mail\Mailable;

class ForgotPassword extends Mailable {
    protected static string $view = "emails.auth.forgot";
}