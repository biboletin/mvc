<?php

namespace Bibo\Mvc\Core\Exception\Custom\Container;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class ContainerItemNotFoundException extends Exception implements NotFoundExceptionInterface
{
}
