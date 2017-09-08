<?php

class userRegistrationEmail extends leafEmail
{
    public static function getVars()
    {
        $vars = [
            'verificationUrl'
        ];
        return $vars;
    }
}