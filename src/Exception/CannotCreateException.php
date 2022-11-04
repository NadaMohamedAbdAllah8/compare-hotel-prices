<?php

namespace App\Exception;

use Exception;

class CannotCreateException extends Exception
{
    protected $object_type;

    public function __construct($object_type)
    {
        $this->object_type = $object_type;

        parent::__construct();
    }

    public function __toString()
    {
        return 'Exception! Could not create  ' . $this->object_type;
    }
}
