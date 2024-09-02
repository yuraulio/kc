<?php

namespace App\Services\Exam;

use App\Contracts\Api\v1\Lesson\ILessonService;
use App\Model\Exam;
use App\Model\ExamSyncData;
use Illuminate\Http\Request;

class ExamResultService
{
    public function fetchLiveResults(Exam $exam)
    {
        $liveResults = [];
        $syncDatas = ExamSyncData::where('exam_id', $exam->id)->get();

        $questions = json_decode($exam->questions, true);
        $index = 1;
        foreach ($syncDatas as $syncData) {
            if ($syncData->finish_at != '0000-00-00 00:00:00') {
                continue;
            }

            $answered = 0;
            $allAnswers = json_decode($syncData->data, true);
            $correct = 0;

            foreach ($allAnswers as $answer) {
                if (trim($answer['given_ans']) != '') {
                    $answered += 1;
                    $correctAnswer = $questions[$answer['q_id']]['correct_answer'];
                    if (is_array($correctAnswer) &&
                            htmlspecialchars_decode($answer['given_ans'], ENT_QUOTES) == htmlspecialchars_decode($correctAnswer[0], ENT_QUOTES)) {
                        $correct += 1;
                    } elseif (htmlspecialchars_decode($answer['given_ans'], ENT_QUOTES) == htmlspecialchars_decode($correctAnswer[0], ENT_QUOTES)) {
                        $correct += 1;
                    }
                }
            }

            $liveResults[] = ['index'=>$index++, 'id'=>$syncData->id, 'name'=>$syncData->student->firstname . ' ' . $syncData->student->lastname,
                'answered' =>  $answered, 'correct' => $correct, 'started_at'=> $syncData->started_at, 'finish_at' => $syncData->finish_at, 'totalAnswers' => count($allAnswers)];
        }

        return $liveResults;
    }
}
