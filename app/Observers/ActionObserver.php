<?php

namespace App\Observers;

use App\Enums\AccountStatusEnum;
use App\Model\Activation;

class ActionObserver
{
    public function created(Activation $activation)
    {
        if ($activation->completed) {
            $activation->user()->update(['account_status' => AccountStatusEnum::Active]);
        }
    }

    public function updated(Activation $activation)
    {
        if ($activation->completed) {
            $activation->user()->update(['account_status' => AccountStatusEnum::Active]);
        }
    }
}
