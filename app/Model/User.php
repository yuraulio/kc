<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Model\Media;
use App\Model\Activation;
use App\Model\Event;
use App\Model\Role;
use App\Model\Instructor;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class User extends Authenticatable

{

    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company',
        'first_name',
        'last_name',
        'birthday',
        'username',
        'mobile',
        'telephone',
        'address',
        'address_num',
        'postcode',
        'city',
        'job_title',
        'partner_id',
        'kc_id',
        'student_type_id',
        'afm',
        'nationality',
        'comments',
        'consent',
        'terms',
        'receipt_details',
        'invoice_details',
        'oexams',
        'country_code'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];


    public function scopeSearchUsers($query, $search_term)
    {

        $query->where(function ($query) use ($search_term) {
            $search_term_str = '%'.implode("%", explode(" ", $search_term)).'%';
            $query->where('email', 'like', $search_term_str)
                ->orWhere('firstname', 'like', $search_term_str)
                ->orWhere('lastname', 'like', $search_term_str);
        });



        return $query->select('id','firstname','lastname','email')->get();
    }


   /**
     * Get the role of the user
     *
     * @return \App\Model\Role
     */
    public function role()
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }

    public function instructor()
    {
        return $this->belongsToMany(Instructor::class, 'instructors_user');
    }

    public function statusAccount()
    {
        //dd('asd');
        return $this->hasOne(Activation::class);
    }

    /**
     * Get the path to the profile picture
     *
     * @return string
     */
    public function profilePicture()
    {
        if ($this->picture) {
            return "/{$this->picture}";
        }

         return 'http://i.pravatar.cc/200';
    }

    /**
     * Check if the user has admin role
     *
     * @return boolean
     */
    public function isAdmin()
    {
        //dd($this->id);
        $role = DB::table('role_users')->where('user_id', $this->id)->first();
        //dd($role->role_id);
        $role = Role::where('id', $role->role_id)->first();
        return $role['id'] == 1;
    }

    /**
     * Check if the user has creator role
     *
     * @return boolean
     */
    public function isCreator()
    {
        return $this->role_id == 2;
    }

    /**
     * Check if the user has user role
     *
     * @return boolean
     */
    public function isMember()
    {
        return $this->role_id == 3;
    }

    /**
     * Get the user's image.
     */
    public function image()
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_user')->withPivot('paid', 'expiration');
    }

    public function ticket()
    {
        return $this->belongsToMany(Ticket::class, 'event_user_ticket')->withPivot('ticket_id');
    }

}
