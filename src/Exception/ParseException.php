<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Exception;


use Exception;

class ParseException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}