<?php

namespace App\Models\program;

use Illuminate\Database\Eloquent\Model;

class Program extends Model {
  protected $table = "program";
  public $timestamps = false;
}

class Programparticipant extends Model {
  protected $table = "programparticipant";
  public $timestamps = false;
}