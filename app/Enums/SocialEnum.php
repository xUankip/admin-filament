<?php

namespace App\Enums;

use ReflectionClass;

class SocialEnum
{
    const FACEBOOK = 'facebook';
    const TIKTOK = 'tiktok';
    const TELEGRAM = 'telegram';
    const SKYPE = 'skype';
    const WEBSITE = 'website';
    const EMAIL = 'email';
    const LINKED_IN = 'linked_in';
    const GITHUB = 'github';
    const YOUTUBE = 'youtube';
    const TWITTER = 'twitter';  // Adding Twitter as a new social media platform
    const INSTAGRAM = 'instagram';

    const PINTEREST = 'pinterest';  // Adding Pinterest
    const SNAPCHAT = 'snapchat';  // Adding Snapchat
    const REDDIT = 'reddit';  // Adding Reddit
    const DISCORD = 'discord';  // Adding Discord
    const OTHER = 'other';  // Adding Discord

    public static function getAllConstants(): array
    {
        $reflection = new ReflectionClass(__CLASS__);
        return $reflection->getConstants();
    }
    static function all(): array
    {
        $ls = self::getAllConstants();

        return array_combine($ls,$ls);
    }
}
