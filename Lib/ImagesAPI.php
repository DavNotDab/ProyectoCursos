<?php
namespace Lib;

use Unsplash;
use Dotenv\Dotenv;

class ImagesAPI
{



}

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

Unsplash\HttpClient::init([
    'applicationId'	=> $_ENV['UNSPLASH_ACCESS_KEY'],
    'secret'	=> $_ENV['UNSPLASH_SECRET_KEY'],
    'callbackUrl'	=> 'https://unsplash.com/oauth/applications/412749',
    'utmSource' => "Courses' Shop"
]);

$search = 'forest';
$page = 3;
$per_page = 15;
$orientation = 'landscape';

Unsplash\Search::photos($search, $page, $per_page, $orientation);