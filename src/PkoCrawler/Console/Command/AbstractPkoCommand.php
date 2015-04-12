<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 * 
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 * 
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace PkoCrawler\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Yaml\Parser;

/**
 * Class AbstractPkoCommand
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AbstractPkoCommand extends Command
{

    protected $configuration;

    public function __construct()
    {
        parent::__construct();
        $this->configuration = $this->getConfiguration();
    }

    protected function getConfiguration()
    {
        $yaml = new Parser();
        $file = ROOT_PATH . '/app/config/parameters.yml';

        return $yaml->parse(file_get_contents($file));
    }
}