<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\LastScope;
use App\Traits\Models\FilterScope;

abstract class BaseModel extends Model
{
    use LastScope, FilterScope;
}
