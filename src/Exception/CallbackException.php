<?php

namespace Omnipay\Ceca\Exception;

/**
 * Description of CallbackException
 *
 * @author Javier Sampedro <jsampedro77@gmail.com>
 */
class CallbackException extends \Exception
{
    protected $message = 'Ceca callback returned an error status code';
}
