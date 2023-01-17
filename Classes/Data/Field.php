<?php

namespace Febis\SimpleTca\Data;

class Field
{
    public $type;
    public $default = null;
    public function __construct($type, $default = null)
    {
        $this->type = $type;
        $this->default = $default;
    }
}
