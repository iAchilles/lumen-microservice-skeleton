<?php
namespace App\Exceptions;

/**
 * ServiceNotExists class
 *
 * @author Igor Manturov Jr. <igor.manturov.jr@gmail.com>
 */
class ServiceNotExists extends \Exception
{
    protected $message = 'The specified microservice does not exist.';
}
