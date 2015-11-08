<?php
namespace Trungtnm\Backend\Core;


interface BackendControllerInterface
{
    /**
     * @param int $id
     * @return mixed
     */
    public function processData($id = 0);
}