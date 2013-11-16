<?php

namespace Rayak;

use Goutte\Client;

/**
 * @author Ayyoub
 */
class PatentsScraper {

    private $client;
    private $config = array();

    public function __construct(Client $client, array $config = null) {
        $this->client = $client;
        $this->config = $config;
    }

    private function getFullPageLink($pageNumber) {
        $website = isset($this->config['website']) ? $this->config['website'] : '';
        $searchPage = isset($this->config['searchPage']) ? $this->config['searchPage'] : '';
        $queryParam = isset($this->config['queryParam']) ? $this->config['queryParam'] : '';
        $pageParam = isset($this->config['pageParam']) ? $this->config['pageParam'] : '';
        $query = isset($this->config['query']) ? $this->config['query'] : '';

        $fullPageLink = $website . $searchPage . '&' . $queryParam . '=' . $query . '&' . $pageParam . '=' . $pageNumber;

        return $fullPageLink;
    }

    private function getPatentLink($patent) {
        $website = isset($this->config['website']) ? $this->config['website'] : '';

        $link = $patent->getAttribute('href');
        $patentLink = $website . $link;
        return $patentLink;
    }

    private function createPatentFile($filePath, $patentContent) {
        $file = fopen($filePath, 'w');
        fputs($file, $patentContent);
        fclose($file);
    }

    public function createPatentsFiles($path) {
        $patentLinkFilter = isset($this->config['patentLinkFilter']) ? $this->config['patentLinkFilter'] : '';
        $patentContentFilter = isset($this->config['patentContentFilter']) ? $this->config['patentContentFilter'] : '';
        $nbPages = isset($this->config['nbPages']) ? $this->config['nbPages'] : 1;

        $nbPatents = 1;

        for ($page = 1; $page <= $nbPages; $page++) {

            $fullPageLink = $this->getFullPageLink($page);

            $patentsCrawler = $this->client->request('GET', $fullPageLink);
            $patents = $patentsCrawler->filter($patentLinkFilter);

            foreach ($patents as $patent) {
                $patentLink = $this->getPatentLink($patent);
                $crawler = $this->client->request('GET', $patentLink);

                $patentCrawler = $crawler->filter($patentContentFilter);
                $patentContent = $patentCrawler->html();

                $filePath = $path . '/' . $nbPatents . '.html';
                $this->createPatentFile($filePath, $patentContent);
                echo "$nbPatents.html Created.\n";
                $nbPatents++;
            }
        }
    }

}
