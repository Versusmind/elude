<?php namespace App\Libraries\Acl\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;

/**
 * Class ModelNotValid
 *
 * @package Libraries\Acl\Exceptions
 */
class ModelNotValid extends \Exception
{
    /**
     * @var MessageBag
     */
    protected $errors;

    public function __construct(MessageBag $errors)
    {
        $this->errors = $errors;

        parent::__construct($errors->toJson());
    }

    /**
     * @return MessageBag
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
