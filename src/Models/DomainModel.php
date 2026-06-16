<?php

namespace Laravel\LaravelDDD\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\LaravelDDD\Factories\HasDomainFactory;

abstract class DomainModel extends Model
{
    use HasDomainFactory;

    protected $guarded = [];
}
