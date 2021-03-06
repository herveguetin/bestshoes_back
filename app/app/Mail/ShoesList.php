<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\DomCrawler\Crawler;

class ShoesList extends Mailable
{
    const LIST_LENGTH = 10;
    const BASE_URL = 'http://www.sarenza.co.uk';
    use Queueable, SerializesModels;
    /**
     * @var []
     */
    public $products;
    /**
     * @var array
     */
    private $args = [
        'for'       => 'women',
        'color'     => 'black',
        'size'      => '3',
        'max_price' => '9999'
    ];
    /**
     * @var string
     */
    private $url = '';

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->buildList();

        return $this
            ->subject('BestShoes - Your shoes list!')
            ->view('shoes-list', ['url' => $this->url]);
    }

    private function buildList()
    {
        $body = $this->requestBody();

        $allProducts = $body->filter('li.vignette')->each(function (Crawler $listItem, $i) {
            return [
                'link' => 'http://www.sarenza.com' . $listItem->filter('a.product-link')->extract(['href'])[0],
                'name' => $listItem->filter('span.model')->text(),
                'price' => $listItem->filter('span.price')->text(),
                'img'  => $this->findSrc($listItem)
            ];
        });

        $this->products = array_slice($allProducts, 0, self::LIST_LENGTH);
    }

    /**
     * @return Crawler
     */
    private function requestBody()
    {
        $client = new \GuzzleHttp\Client();
        $this->url = sprintf(
            '%s/store/product/list/view?search=%s+%s&size=%s&selling_price=0&selling_price=%s',
            self::BASE_URL,
            $this->args['for'],
            $this->args['color'],
            $this->args['size'],
            $this->args['max_price']
        );
        $res = $client->get($this->url);

        return new Crawler($res->getBody()->getContents());
    }

    /**
     * @param $node
     * @return mixed
     */
    private function findSrc(Crawler $node)
    {
        $src = $node->filter('a.product-link img')->extract(['src'])[0];
        $dataOriginal = $node->filter('a.product-link img')->extract(['data-original'])[0];

        return (strpos($src, 'base64') === false) ? $src : $dataOriginal;
    }

    /**
     * @param array $args
     */
    public function args(array $args)
    {
        $this->args = array_merge($this->args, $args);
    }
}
