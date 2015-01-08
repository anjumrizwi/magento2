<?php
/**
 * @copyright Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 */

require_once __DIR__ . '/bootstrap.php';

use Magento\Framework\Test\Utility\Files;
use Magento\Tools\Dependency\ServiceLocator;

try {
    $console = new \Zend_Console_Getopt(['directory|d=s' => 'Path to base directory for parsing']);
    $console->parse();

    $directory = $console->getOption('directory') ?: BP;

    Files::setInstance(new \Magento\Framework\Test\Utility\Files($directory));
    $filesForParse = Files::init()->getComposerFiles('code', false);

    ServiceLocator::getDependenciesReportBuilder()->build(
        [
            'parse' => ['files_for_parse' => $filesForParse],
            'write' => ['report_filename' => 'modules-dependencies.csv'],
        ]
    );

    fwrite(STDOUT, PHP_EOL . 'Report successfully processed.' . PHP_EOL);
} catch (\Zend_Console_Getopt_Exception $e) {
    fwrite(STDERR, $e->getUsageMessage() . PHP_EOL);
    exit(1);
} catch (\Exception $e) {
    fwrite(STDERR, 'Please, check passed path. Dependencies report generator failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}