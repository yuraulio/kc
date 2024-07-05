<?php

namespace App\Http\Controllers;

use App\Model\Skill;
use App\Model\User;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('manage-users', User::class);

        $skills = Skill::query()->when($request->search, function ($query) {
            $query->where('name', 'like', '%' . request('search') . '%');
        })->paginate($request->perPage ?: 10);

        return view('admin.skill.index', ['skills' => $skills]);
    }

    public function create()
    {
        return view('admin.skill.create');
    }

    public function store(Request $request)
    {
        $this->authorize('manage-users', User::class);

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Skill::create($data);

        return redirect()->route('skill.index')->withStatus(__('Skill successfully created.'));
    }

    public function edit(Skill $skill)
    {
        $this->authorize('manage-users', User::class);

        return view('admin.skill.edit', compact('skill'));
    }

    public function update(Request $request, Skill $skill)
    {
        $this->authorize('manage-users', User::class);

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $skill->update($data);

        return redirect()->route('skill.index')->withStatus(__('Skill successfully updated.'));
    }

    public function destroy(Skill $skill)
    {
        if (!$skill->users->isEmpty()) {
            return redirect()->route('skill.index')->withErrors(__('This career has items attached and can\'t be deleted.'));
        }

        $skill->delete();

        return redirect()->route('skill.index')->withStatus(__('Skill successfully deleted.'));
    }
}
