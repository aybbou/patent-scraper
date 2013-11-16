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
    'searchPage'=>'/result.html?srch=ezsrch&search=Search&pn=&apn=&ttl=&abst=&aclm=&spec=&apd=&apdto=&isd=9%2F15%2F2013&isdto=11%2F15%2F2013&prir=&ccl=&icl=&in=&icn=&is=&ic=&an=&acn=&as=&ac=&ref=&fref=&oref=&parn=&pex=&asex=&agt=&uspat=on&date_range=all&stemming=on&sort=relevance',
    'queryParam'=>'all',
    'pageParam' => 'p'
);

$fpoScraper = new PatentsScraper($client, $fpoconfig);
$fpoScraper->createPatentsFiles(__DIR__ . '/fporesults');

echo 'Done !';