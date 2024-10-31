<?php

namespace App\Services\Messaging;

use App\Model\Email;
use App\Model\MessageCategory;
use Illuminate\Http\Request;
use Storage;

class EmailService
{
    public function createOrUpdate(Request $request)
    {
        $email = Email::updateOrCreate(
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
        if (count($request->categories)) {
            $email->messaging_categories()->detach();
            foreach ($request->categories as $category) {
                $messageCategory = MessageCategory::find($category['id']);
                $messageCategory->email()->save($email);
            }
        }

        return $email;
    }

    public function delete(Email $email)
    {
        return $email->delete();
    }
}
