<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Статусы предприятия
 * Class Status
 * @package App
 */
class Status extends Model
{
  /**
   * @var array
   */
  protected $fillable = ['name'];
}