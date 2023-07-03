<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;

class StaffController extends Controller
{
    //
    public function allStaffs()
    {
        return  Staff::get()->map(function($item) {
            $item->full_name = $item->number ? $item->number . ' - ' .  $item->name : $item->name;
            return $item;
        });
    }
}
