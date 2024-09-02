<?php

namespace App\Services\Exam;

use App\Contracts\Api\v1\Lesson\ILessonService;
use App\Model\Exam;
use App\Model\ExamSyncData;
use Illuminate\Http\Request;

class ExamCategory
{
    const EVENT = 1;
    const CAREER_PATH = 2;
    const TOPIC = 3;
}

class ExamService
{
    public function delete(Exam $exam)
    {
        if (!$exam->results->isEmpty()) {
            return ['message'=>'This exam has items attached and can\'t be deleted.'];
        }

        return $exam->delete();
    }

    public function updateQuestions(Request $request, Exam $exam)
    {
        $questions = $request->questions;
        $exam->questions = json_encode($questions);
        $exam->save();

        return response()->json(['data' => [
            'questions' => json_decode($exam->questions, true),
        ]]);
    }

    public function createOrUpdate(Request $request)
    {
        $input = $request->except('course_id', 'topic_id', 'exam_id');
        if (empty($request->id)) {
            $exam = Exam::firstOrCreate(['exam_name'=>$input['exam_name']]);
        } else {
            $exam = Exam::findOrFail($request->id);
        }

        $input['indicate_crt_incrt_answers'] = isset($input['indicate_crt_incrt_answers']) && $input['indicate_crt_incrt_answers'] == 1 ? 1 : 0;
        $input['random_questions'] = isset($input['random_questions']) && $input['random_questions'] == 1 ? 1 : 0;
        $input['display_crt_answers'] = isset($input['display_crt_answers']) && $input['display_crt_answers'] == 1 ? 1 : 0;
        $input['random_answers'] = isset($input['random_answers']) && $input['random_answers'] == 1 ? 1 : 0;

        $input['status'] = $request->status && $request->status = 1 ? true : false;
        $input['publish_time'] = date('Y-m-d H:i', strtotime($request->publish_time));
        $exam->update($input);
        $exam->topic()->detach();
        $exam->event()->detach();
        $exam->career_path()->detach();

        if ($exam->exam_category == 1) {
            $exam->event()->attach($request->event_id);
        } elseif ($exam->exam_category == 2) {
            $exam->career_path()->attach($request->career_path_id);
        } elseif ($exam->exam_category == 3) {
            $exam->topic()->attach($request->topic_id);
        }

        return ['data' => ['message'=>['Exam updated successfully'], 'id' => empty($request->id) ? $exam->id : null]];
    }

    public function getExamWithRelations(Exam $exam)
    {
        if (count($exam->event)) {
            $exam->event_id = $exam->event->first()->id;
            $exam->exam_category = ExamCategory::EVENT;
        } elseif (count($exam->career_path)) {
            $exam->career_path_id = $exam->career_path->first()->id;
            $exam->exam_category = ExamCategory::CAREER_PATH;
        } elseif (count($exam->topic)) {
            $exam->topic_id = $exam->topic->first()->id;
            $exam->exam_category = ExamCategory::TOPIC;
        }

        return $exam->makeVisible('questions');
    }

    public function duplicate(Exam $exam)
    {
        $exam = $exam->replicate();
        $exam->status = false;
        $exam->push();

        return $exam;
    }
}
