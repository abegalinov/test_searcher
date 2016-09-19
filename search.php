<?php
ini_set('display_errors', 'yes');
include('Exceptions.php');
include('Searcher.php');
include('BaseSource.php');
include('DarazPkSource.php');
include('SnapdealComSource.php');

use TestTask\Exceptions;
use TestTask\Searcher;
use TestTask\DarazPkSource;
use TestTask\SnapdealComSource;

try {
    if(!isset($_REQUEST['q']) || !trim($_REQUEST['q']))
        throw new Exceptions\BadQueryException("Empty query", 1);

    if(preg_match("/[^\w]/", $_REQUEST['q']))
        throw new Exceptions\BadQueryException("Incorrect query", 2);

    $searcher = new Searcher();

    $searcher->addSource(new DarazPkSource());
    $searcher->addSource(new SnapdealComSource());

    //$searcher->setLogFile("results.log");

    $searcher->processQuery($_REQUEST['q']);

    echo $searcher->getResultsJson();

} catch (Exceptions\BadQueryException $e) {
    echo $e->toJson();
} catch (Exception $e) {
    error_log($e->__toString());
    $e = new Exceptions\SystemException('Service temporarily unavailable');
    echo $e->toJson();
}
