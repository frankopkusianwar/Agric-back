<?php

namespace App\Utils;

use App\Models\InputSupplier;
use Laravel\Lumen\Routing\Controller as BaseController;

class InputSupplierHelpers extends BaseController
{
    /**
     * delete input
     * @param string $name
     * @return bool query result
     */
    public static function deleteInput($name)
    {
        $deleteInput = InputSupplier::where('name', $name)->delete();
        return $deleteInput ? true : false;
    }
}
