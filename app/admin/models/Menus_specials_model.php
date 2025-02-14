<?php namespace Admin\Models;

use Carbon\Carbon;
use Model;

/**
 * Menu Specials Model Class
 * @package Admin
 */
class Menus_specials_model extends Model
{
    /**
     * @var string The database table name
     */
    protected $table = 'menus_specials';

    protected $primaryKey = 'special_id';

    protected $fillable = ['menu_id', 'start_date', 'end_date', 'special_price', 'special_status'];

    public $dates = ['start_date', 'end_date'];

    public $casts = [
        'recurring_every' => 'array',
    ];

    public function getRecurringEveryOptions()
    {
        return ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    }

    public function getPrettyEndDateAttribute()
    {
        if ($this->isRecurring() OR !$this->end_date)
            return null;

        return mdate(setting('date_format'), $this->end_date->getTimestamp());
    }

    public function active()
    {
        if (!$this->special_status)
            return FALSE;

        return !($this->isExpired() === TRUE);
    }

    public function daysRemaining()
    {
        if ($this->isRecurring() OR !$this->end_date->greaterThan(Carbon::now()))
            return 0;

        return $this->end_date->diffForHumans();
    }

    public function isRecurring()
    {
        return $this->validity == 'recurring';
    }

    public function isExpired()
    {
        $now = Carbon::now();

        switch ($this->validity) {
            case 'period':
                return !$now->between($this->start_date, $this->end_date);
            case 'recurring':
                if (!in_array($now->format('w'), $this->recurring_every))
                    return TRUE;

                $start = $now->copy()->setTimeFromTimeString($this->recurring_from);
                $end = $now->copy()->setTimeFromTimeString($this->recurring_to);

                return !$now->between($start, $end);
        }
    }
}