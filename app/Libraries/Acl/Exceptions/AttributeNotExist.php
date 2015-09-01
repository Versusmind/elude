<?php namespace App\Libraries\Acl\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\MessageBag;

/**
 * Class ModelNotValid
 *
 * @package Libraries\Acl\Exceptions
 */
class AttributeNotExist extends ModelNotValid
{
    public function __construct(Model $model, $attributes)
    {

        parent::__construct(new MessageBag([$attributes . 'does not exist or is not fillable for class ' . get_class($model)]));
    }
}
