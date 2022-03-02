<?php

declare (strict_types=1);
namespace App\Model;

/**
 * 代付模型
 *
 * @property int $id 
 * @property string $order_no 
 * @property int $admin_id 
 * @property string $channel 
 * @property int $amount 
 * @property float $exchange_rate 
 * @property string $name 
 * @property string $bank_account 
 * @property string $bank_name 
 * @property string $bank_code 
 * @property string $open_province 
 * @property string $open_city 
 * @property int $status 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Defray extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'defray';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'admin_id' => 'integer', 'amount' => 'integer', 'exchange_rate' => 'float', 'status' => 'integer', 'created_at' => 'date:Y-m-d H:i:s', 'updated_at' => 'date:Y-m-d H:i:s'];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}