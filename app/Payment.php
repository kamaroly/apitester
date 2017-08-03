<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
	/**
	 * Special connection to havanao databse
	 * @var string
	 */
    protected $connection = 'havanao_connection';

    /**
     * Table that holds payments
     * @var string
     */
    protected $table 	  = 'payments';
}
