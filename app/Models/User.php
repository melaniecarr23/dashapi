<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'patient_id',
        'first',
        'last',
        'sender',
        'phone',
        'square_link',
        'membership_link',
        'box_link',
        'subscription_link',
        'pmts_last_exported',
        'interval'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
    public function patients(): HasMany
    {
        $patients = $this->hasMany(Patient::class, 'doctor_id');

        return $patients;
    }
    public function secondaries(): HasMany
    {
        $patients = $this->hasMany(Patient::class, 'secondary_id');

        return $patients;
    }
    public function appts(): HasMany
    {
        return $this->hasMany(Appt::class, 'doctor_id');
    }

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class, 'doctor_id');
    }
    public function officehours(): HasMany
    {
        return $this->hasMany(Officehour::class, 'doctor_id');
    }
    public function invoice_items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'doctor_id');
    }

    public function invoicesDue(): HasMany
    {
        return $this->hasMany(Invoice::class, 'payer_id');
    }
    public function invoicesOwed(): HasMany
    {
        return $this->hasMany(Invoice::class, 'payee_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'doctor_id');
    }
    public function closures(): HasMany
    {
        return $this->hasMany(ModifiedHour::class, 'doctor_id');
    }
    public function clients() {

        return $this->secondaries()->get()->merge($this->patients()->get());
    }
    /**
     * Returns primary and secondary patients
     * who have someone else on their plan
     */
    public function parents() {
        $filtered = $this->clients()->filter(function (Patient $pt) {
            return $pt->members() && $pt->children()->count() > 1;
        });
        return $filtered;
    }

}
