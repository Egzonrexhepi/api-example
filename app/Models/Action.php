<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Action
 * 
 * @property int $id
 * @property int $customer_id
 * @property float $amount
 * @property Carbon $date
 * @property int $type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Customer $customer
 *
 * @package App\Models
 */
class Action extends Model
{
	protected $table = 'actions';

	protected $casts = [
		'customer_id' => 'int',
		'amount' => 'float',
		'type' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'customer_id',
		'amount',
		'date',
		'type'
	];

	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}
}
