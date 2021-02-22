<?php

namespace App;

use Mail;

class Email {

    public static function sendEmail($to, $subject, $body, $attachment = null)
    {
        Mail::queue('emails.send', $body, function($message) use($to, $subject, $attachment) {

            $message->to($to)
                ->subject($subject)
                ->from(auth()->user()->email, auth()->user()->username)
                ->bcc(auth()->user()->email, auth()->user()->username);

            if ($attachment) {
                $message->attach($attachment->attachment_filename);
            }

        });
    }

}

?>
