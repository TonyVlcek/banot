<?php


declare(strict_types=1);

namespace Banot\Scraper\Utils;

class DoSomeWork
{
    public static function doIt()
    {
        password_hash(random_bytes(255), PASSWORD_BCRYPT, ["cost" => 15]);
    }
}
