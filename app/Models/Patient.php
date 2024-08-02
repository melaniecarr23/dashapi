<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

/**
 * Class Patient
 *
 * @property int $id
 * @property int|null $parent_id
 * @property int|null $plan_id
 * @property int $doctor_id
 * @property int|null $secondary_id
 * @property int|null $payment_day
 * @property string|null $first
 * @property string|null $last
 * @property string|null $nickname
 * @property string|null $home
 * @property string|null $work
 * @property string|null $cell
 * @property string|null $email
 * @property string|null $address
 * @property string|null $city
 * @property string|null $state
 * @property string|null $zip
 * @property Carbon|null $dob
 * @property Carbon|null $startdate
 * @property string|null $cc
 * @property string|null $listing
 * @property string $active
 * @property string|null $recall
 * @property string|null $recall_note
 * @property int $referred_by
 * @property string|null $referral_tag
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection|Appt[] $appts
 * @property Collection|User[] $users
 * @package App\Models
 * @property Patient $parent
 * @property-read int|null $appts_count
 * @property-read Collection|Payment[] $payments
 * @property-read Collection|Patient[] $children
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder|Patient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient query()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereRecall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereRecallNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereCc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereCell($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereSecondaryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient wherePaymentDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereLast($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereListing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereReferralTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereReferredBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereStartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereWork($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereZip($value)
 * @mixin \Eloquent
 */
class Patient extends Model
{
    use Notifiable;

    use SoftDeletes;
	protected $table = 'patient';
    protected string $parentColumn = 'parent_id';
    public $timestamps = true;


    protected $dates = [
		'dob',
		'startdate'
	];

	protected $casts = [
        'startdate' => 'datetime:Y-m-d'
    ];

	protected $fillable = [
        'parent_id',
        'plan_id',
        'payment_day',
		'first',
		'last',
		'nickname',
		'home',
		'work',
		'cell',
		'email',
		'address',
		'city',
		'state',
		'zip',
		'dob',
		'startdate',
		'cc',
		'listing',
		'active',
        'recall',
        'recall_note',
        'referred_by',
        'referral_tag',
        'doctor_id',
        'secondary_id'
	];

    protected static function boot()
    {
        parent::boot();
        // set self as parent id if none selected
        static::created(function ($patient) {
            if ($patient->parent_id === null) {
            $patient->parent_id = $patient->id;
            $patient->save();
            }
        });
    }



    /**
     * Return collection of parent members for family dropdown.
     *
     * @return Collection
     */
    public static function families(): Collection
    {
        return Patient::has('children', '>',1)->orderBy('nickname');
    }

    /**
     * Get only single members.
     *
     * @return Collection
     */
    public static function singles(): Collection
    {
        return Patient::has('children','<',2)
            ->orderBy('nickname');
    }

    /**
     * Scope a query to return children of the parent.
     *
     * @param Builder $query
     * @param Patient $patient
     * @return Builder
     */
    public function scopeMembers(Builder $query): Builder{

//        return $query->where('parent_id','=',$parent_id);
        return $query->whereColumn('parent_id','id');
    }

	public function appts(): HasMany
    {
		return $this->hasMany(Appt::class)->orderBy('date_time','desc');
	}

    public function lastVisit(): HasOne
    {
        return $this->hasOne(Appt::class)->latest();
    }

    public function notifications(): BelongsToMany
    {
        return $this->belongsToMany(Notification::class)->withPivot(['channel_id']);
    }

	public function payments(): HasMany
    {
		return $this->hasMany(Payment::class)->orderBy('pmt_duedate', 'desc');
	}

	public function referrer(): HasOne
    {
	    return $this->hasOne(Patient::class, 'id','referred_by');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class,'plan_id');
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
    // members of the family
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function family()
    {
        $parent = $this->parent ?: $this;
        return $parent->hasMany(self::class, 'id', 'parent_id')
            ->union($parent->children())->without(self::class);
    }

    public function doctor() {
        return $this->belongsTo(User::class,'doctor_id');
    }

//    secondary doctor
    public function secondary() {
        return $this->belongsTo(User::class, 'secondary_id');
    }

    /**
     * Gets primary AND secondary patients
     * @param Builder $query
     * @param $doctor_id
     */
    public function scopeDoc(Builder $query, $doctor_id) {
        $query->where('doctor_id',$doctor_id)->orWhere('secondary_id',$doctor_id);
    }

    // NOTIFICATION OPTIONS
    /**
     * Route notifications for the Twilio channel.
     *
     * @return string|null
     */
    public function routeNotificationForTwilio()
    {
        return $this->cell ?: null;
    }

    /**
     * Route notifications for the mail channel.
     *
     * @return string|null
     */
    public function routeNotificationForMail()
    {
        return $this->email ?: null;
    }

    /**
     * Route notifications for phone calls.
     *
     * @return string|null
     */
    public function routeNotificationForPhone()
    {
        return (!$this->cell && $this->home) ? $this->home : null;
    }


}
