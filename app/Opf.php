<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opf extends Model
{
  /**
   * @var string
   */
  protected $table = 'opf';

  /**
   * @var array
   */
  protected $fillable = ['name'];
}
