<?php

namespace App\Helpers\Mail;

use App\Helpers\Curl\Http;

class Builder {
    private array $with = [];
    private array $attachments = [];
    private string $subject = "";
    
    public function __construct(private string $view, private string $fromEmail, private string $fromName)
    {
    }

    public function withAttachments(array $attachments) {
        $this->attachments = $attachments;
        return $this;
    }
    public function with(array $data) {
        $this->with = $data;
        return $this;
    }
    public function subject(string $subject) {
        $this->subject = $subject;
        return $this;
    }
    public function send(string|array $email) {
        if (gettype($email) === "array") $to = array_map(fn (string $email) => ["Email" => $email], $email);
        else $to = [["Email" => $email]];
        $config = config("email");
        $content = view($this->view, $this->with, true);
        $text = strip_tags($content);

        $response = Http::withUser("{$config["mailjet"]["client"]}:{$config["mailjet"]["secret"]}")->post("https://api.mailjet.com/v3.1/send", [
            "Messages" => [[
                "From" => [
                    "Email" => $this->fromEmail,
                    "Name" => $this->fromName
                ],
                "To" => $to,
                "Subject" => $this->subject,
                "TextPart" => $text,
                "HTMLPart" => $content,
                "Attachments" => array_map(fn (array $attachment) => [
                    "ContentType" => $attachment["ContentType"],
                    "Filename" => $attachment["Filename"],
                    "Base64Content" => base64_encode(file_get_contents($attachment["File"]))
                ], $this->attachments)
            ]]
        ]);
    }
}