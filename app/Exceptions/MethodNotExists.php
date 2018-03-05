<?php
namespace App\Exceptions;

/**
 * MethodNotExists class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class MethodNotExists extends \Exception
{
    protected $message = 'The specified method does not exist.';
}
