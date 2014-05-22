<?php

namespace Rok\TestBundle\Services;

class WidgetsService {

    /**
     * @var string $feedNamespace
     */
    protected $feedNamespace;

    /**
     * @var string $feedUrl
     */
    protected $feedUrl;

    /**
     * @param string $feedUrl
     * @param string $feedNamespace
     */
    public function __construct($feedUrl, $feedNamespace){

        $this->feedNamespace = $feedNamespace;
        $this->feedUrl = $feedUrl;

    }

    /**
     * @param $feedUrl
     * @return array
     */
    public function getBlogItems($feedUrl = false) {

        $feedUrl = $feedUrl ? $feedUrl : $this->feedUrl;


        $items = $this->readXml($feedUrl);

        /** sort by title */
        uasort($items, function($a, $b){
            $cmp = strcmp($a['title'], $b['title']);
            if($cmp == 0) {
                return 0;
            }elseif($cmp < 0){
                return -1;
            }else{
                return 1;
            }
        });

        return array_values($items);

    }

    /**
     * @param $xmlUrl url of xml
     * @return array
     */
    protected  function readXml($xmlUrl) {

        $source_xml = simplexml_load_file($xmlUrl);

        /** @var $namespace must define to be able to get image */

        $data = array();
        $n = 0;

        foreach ($source_xml->channel->item as $rss) {
            $data[$n] = array(
                'title'          => (string)$rss->title,
                'description'    => (string)$rss->description,
                'link'           => (string)$rss->link,
            );

            /** @var $image get media:thumbnail */
            $image = $rss->children($this->feedNamespace)->thumbnail[1]->attributes();
            $data[$n]['image'] = (string)$image['url'];
            $n++;
        }

        return $data;

    }

}