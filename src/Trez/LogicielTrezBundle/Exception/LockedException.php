<?php

namespace Trez\LogicielTrezBundle\Exception;

/*
    This exception occurs when user try to modify a locked object
*/
class LockedException extends \RuntimeException
{
}