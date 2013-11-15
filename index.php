<?php

require_once 'goutte.phar';
require_once __DIR__ . '/src/autoload.php';

use Goutte\Client;
use Rayak\PatentsScraper;

$client = new Client();

$fpoconfig = array(
    'website' => 'http://www.freepatentsonline.com',
    'query' => 'RFID',
    'nbPages' => 5,
    'patentLinkFilter' => '.listing_table a',
    'patentContentFilter' => '.document-details-wrapper',
    'searchPage' => '/result.html?sort=relevance&srch=top&submit=&patents=on',
    'queryParam' => 'query_txt',
    'pageParam' => 'p'
);

$fpoScraper = new PatentsScraper($client, $fpoconfig);
$fpoScraper->createPatentsFiles(__DIR__ . '/fporesults');