<?php

namespace App\Jobs;

use App\Model\User;
use App\Notifications\ErrorSlack;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Storage;
use Intervention\Image\ImageManagerStatic as Image;

class UploadImageConvertWebp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $path;
    private $name;
    private $user_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path, $name, $user_id = 0)
    {
        $this->path = $path;
        $this->name = $name;
        $this->user_id = $user_id;

        //dd($this->path.$this->name);

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ext = explode('.',$this->name)[count(explode('.',$this->name)) - 1];

        if($ext == 'JPG' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $ext == 'bmp'){

            $destination = str_replace($ext,'webp',$this->path.$this->name);

            if(Storage::exists(public_path('/uploads/').$this->path.$this->name)){
                $a = Image::make(public_path('/uploads/').$this->path.$this->name)->stream("webp", config('app.WEBP_IMAGE_QUALITY'));
                Storage::disk('public')->put($destination, $a, 'public');
            }else{
                $message = 'Error with the conversion of image. Please <@'.env('SLACK_MEMEBER_ID_RESPONSIBLE_OF_ERRORS_MANAGING', '').'>, check why the image located '.public_path('/uploads/').$this->path.$this->name.' is not visible.';
                $user = User::first();
                if($this->user_id && $this->user_id != 0){
                    $userProblems = User::find($this->user_id);
                    if($userProblems){
                        $message .= ' Related with the user '.$user->name.' '.$user->email.'.';
                    }
                }
                $user->notify(new ErrorSlack());
            }
        }
    }
}
