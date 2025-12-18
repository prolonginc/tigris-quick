<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Cookie\CookieJar;

class MygrantScraperService
{
    protected string $baseUrl = 'https://www.mygrantglassonline.com';
    protected $client;
    protected $cookieJar;

    public function __construct()
    {
        // Create a reusable cookie jar for session persistence
        $this->cookieJar = new CookieJar();

        // Create HTTP client with cookie support
        $this->client = Http::withOptions([
            'cookies' => $this->cookieJar,
        ])->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (compatible; MygrantScraperBot/1.0)',
        ]);
    }

public function loginAndFetchPrices(string $username, string $password)
{

    // Step 1: Load login page to get __VIEWSTATE and other hidden fields
    $loginPage = $this->client->get('https://www.google.com');
    dd($this->baseUrl . '/pages/login.aspx',$loginPage);
    $crawler = new Crawler($loginPage->body());

    // Extract ASP.NET hidden fields (they change every request)
    $viewState = $crawler->filter('input[name="__VIEWSTATE"]')->attr('value') ?? '';
    dd($viewState);
    $eventValidation = $crawler->filter('input[name="__EVENTVALIDATION"]')->attr('value') ?? '';
    $viewStateGenerator = $crawler->filter('input[name="__VIEWSTATEGENERATOR"]')->attr('value') ?? '';

    // Step 2: Prepare form data (based on actual field names)
    $formData = [
        '__VIEWSTATE' => $viewState,
        '__VIEWSTATEGENERATOR' => $viewStateGenerator,
        '__EVENTVALIDATION' => $eventValidation,
        // These must match the form input names in the HTML
        'ctl00$ContentPlaceHolder1$UserName' => $username,
        'ctl00$ContentPlaceHolder1$Password' => $password,
        'ctl00$ContentPlaceHolder1$LoginButton' => 'Login',
    ];
        dd($formData);

    // Step 3: Submit the login form
    $loginResponse = $this->client
        ->asForm()
        ->withHeaders([
            'Referer' => $this->baseUrl . '/login.aspx',
            'Origin' => $this->baseUrl,
        ])
        ->post($this->baseUrl . '/login.aspx', $formData);
    dd($loginResponse);
    if ($loginResponse->failed()) {
        throw new \Exception('Login failed: ' . $loginResponse->status());
    }

    // Step 4: Check if login was successful
    if (str_contains($loginResponse->body(), 'Invalid username or password')) {
        throw new \Exception('Invalid credentials.');
    }

    // Step 5: Fetch the prices page
    $pricesPage = $this->client->get($this->baseUrl . '/prices.aspx');
    if ($pricesPage->failed()) {
        throw new \Exception('Failed to fetch prices page.');
    }

    // Step 6: Parse the prices table (adjust selector)
    $crawler = new Crawler($pricesPage->body());
    $items = [];

    $crawler->filter('.price-row')->each(function (Crawler $node) use (&$items) {
        $items[] = [
            'item' => $node->filter('.item-name')->text('N/A'),
            'price' => $node->filter('.item-price')->text('N/A'),
        ];
    });

    return $items;
}


}
