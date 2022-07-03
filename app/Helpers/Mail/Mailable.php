<?php

namespace App\Helpers\Mail;

use Exception;

abstract class Mailable {
    protected static string $view;
    protected static string $fromName;
    protected static string $fromEmail;

    private static function getBuilder() {
        if (!isset(static::$view)) throw new Exception("Property \"view\" is required.", 1);
        $config = config("email");

        return new Builder(
            static::$view,
            static::$fromEmail ?? $config["from"]["email"],
            static::$fromName ?? $config["from"]["name"]
        );
    }

    static function withAttachments(array $attachments) {
        return static::getBuilder()->withAttachments($attachments);
    }
    static function with(array $data) {
        return static::getBuilder()->with($data);
    }
    static function subject(string $subject) {
        return static::getBuilder()->subject($subject);
    }
    static function send(string $email) {
        static::getBuilder()->send($email);
    }
}
