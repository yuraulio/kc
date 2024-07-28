<?php

namespace App\Http\Controllers\Theme;

use App\Enums\WorkExperience;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class MyPublicProfileController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate([
            'social_links' => ['array'],
            'social_links.linkedin' => ['required_if:is_public_profile_enabled,true', 'nullable', 'string', 'url'],
            'biography' => ['required_if:is_public_profile_enabled,true', 'nullable', 'string'],
            'is_employee' => ['boolean', 'required_without_all:is_freelancer'],
            'is_freelancer' => ['boolean', 'required_without_all:is_employee'],
            'will_work_remote' => ['boolean', 'required_without_all:will_work_in_person'],
            'will_work_in_person' => ['boolean', 'required_without_all:will_work_remote'],
            'city_ids' => ['array', 'required_if:will_work_in_person,true'],
            'city_ids.*' => ['integer', 'exists:cities,id'],
            'skill_ids' => ['array', 'required_if:is_public_profile_enabled,true'],
            'skill_ids.*' => ['integer', 'exists:skills,id'],
            'career_path_ids' => ['array', 'required_if:is_public_profile_enabled,true'],
            'career_path_ids.*' => ['integer', 'exists:career_paths,id'],
            'work_experience' => ['required_if:is_public_profile_enabled,true', Rule::in(WorkExperience::cases())],
            'is_public_profile_enabled' => ['boolean'],
        ], [
            'social_links.linkedin.required_if' => 'The LinkedIn field is required.',
            'biography.required_if' => 'The :attribute field is required.',
            'is_employee.required_without_all' => 'At least one must be checked.',
            'is_freelancer.required_without_all' => 'At least one must be checked.',
            'city_ids.required_if' => 'At least one city must be chosen.',
            'skill_ids.required_if' => 'At least one skill must be chosen.',
            'career_path_ids.required_if' => 'At least one career path must be chosen.',
            'work_experience.required_if' =>  'At least one must be chosen.',
        ]);

        $request->user()->update($data);

        $request->user()->skills()->sync($data['skill_ids'] ?? []);
        $request->user()->careerPaths()->sync($data['career_path_ids'] ?? []);
        $request->user()->cities()->sync($data['city_ids'] ?? []);

        Session::flash('opmessage', 'Your public profile settings have been updated!');
        Session::flash('opstatus', 1);

        return response()->json([
            'message' => 'Your public profile settings have been updated!',
        ]);
    }
}
