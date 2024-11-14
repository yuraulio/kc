<?php

namespace App\Model;

use App\Model\Email;
use App\Model\Event;
use App\Model\Lesson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTrigger extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'trigger_filters' => 'array',
    ];

    public function email()
    {
        return $this->belongsTo(Email::class);
    }

    public function lesson_trigger_logs()
    {
        return $this->morphedByMany(Lesson::class, 'email_triggerables');
    }

    public function course_trigger_logs()
    {
        return $this->morphedByMany(Event::class, 'email_triggerables');
    }
}
