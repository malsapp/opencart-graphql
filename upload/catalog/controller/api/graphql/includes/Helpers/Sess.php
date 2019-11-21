<?php

namespace GQL\Helpers;

class Sess extends \Session
{
    public function get($var)
    {
        global $reg;
        return $reg->get($var);
    }
}