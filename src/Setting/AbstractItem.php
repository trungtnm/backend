<?php

namespace Trungtnm\Backend\Setting;

abstract class AbstractItem
{
    protected $type = 'text';

    protected $help = '';

    protected $label = '';

    protected $value = '';
}