<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Customer
 * 
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $gender
 * @property string $email
 * @property string $country
 * @property float $balance
 * @property float $bonus_balance
 * @property float $bonus_percentage
 * @property int $deposit_count
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection|Action[] $actions
 *
 * @package App\Models
 */
class Customer extends Model
{
	protected $table = 'customers';

	protected $casts = [
		'balance' => 'float',
		'bonus_balance' => 'float',
		'bonus_percentage' => 'float',
		'deposit_count' => 'int'
	];

	protected $fillable = [
		'first_name',
		'last_name',
		'gender',
		'email',
		'country',
		'balance',
		'bonus_balance',
		'bonus_percentage',
		'deposit_count'
	];

	public function actions()
	{
		return $this->hasMany(Action::class);
	}
}
