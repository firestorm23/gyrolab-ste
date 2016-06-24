<?php

namespace SiteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImageControllerTest extends WebTestCase
{
    public function testUploadraw()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'upload_raw');
    }

}
