<?php

namespace Rok\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route(
     *     "/{_format}",
     *     defaults = { "_format" = "html" },
     *     requirements = { "_format" = "html|json" },
     *     name = "feed"
     * )
     * @Template()
     */
    public function indexAction()
    {

        $data = $this->get('rok_test.widgets')->getBlogItems('http://feeds.bbci.co.uk/news/technology/rss.xml');

        return array(
            'items' => $data
        );
    }
}
