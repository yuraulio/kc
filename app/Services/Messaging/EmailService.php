<?php

namespace App\Services\Messaging;

use App\Model\Email;
use Illuminate\Http\Request;
use Storage;

class EmailService
{
    public function createOrUpdate(Request $request)
    {
        return Email::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'title' => $request->title,
                'status' => $request->status,
                'predefined_trigger' => $request->predefined_trigger,
                'template' => $request->template,
                'description' => $request->description,
                'creator_id' => $request->creator_id,
                'filter_criteria' => $request->filter_criteria,
            ]
        );
    }

    public function delete(Email $email)
    {
        return $email->delete();
    }
}
