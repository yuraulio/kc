<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExamRequest;
use App\Model\Event;
use App\Model\Exam;
use App\Model\ExamResult;
use App\Model\ExamSyncData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Storage;
use Validator;
use ZipArchive;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();

        $exams = Exam::withCount('results')->get();

        return view('admin.exams.index', ['exams' => $exams, 'user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $events = Event::all();
        $exam = new Exam;
        $edit = false;
        $event_edit = false;
        $liveResults = [];

        foreach ($events as $event) {
            $eventInfo = $event->event_info();
            $date = '';

            if (isset($eventInfo['inclass']['dates']['text'])) {
                $date = $eventInfo['inclass']['dates']['text'];
            }

            $eventsData[$event->id] = trim($event->htmlTitle . ' ' . $date);
        }

        return view('admin.exams.create', ['user' => $user, 'events' => $eventsData, 'edit' => $edit, 'exam' => $exam, 'event_id'=>$event_edit, 'liveResults' => $liveResults]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExamRequest $request, Exam $model)
    {
        $input = $request->all();
        $input['publish_time'] = date('Y-m-d H:i', strtotime($request->publish_time));
        $input['status'] = $request->status && $request->status = 'on' ? true : false;
        $exam = $model->create($input);
        $exam->event()->attach($request->event_id);

        return redirect('admin/exams/' . $exam->id . '/edit');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function show(Exam $exam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function edit(Exam $exam)
    {
        $user = Auth::user();
        $events = Event::all();
        $edit = true;
        $event_edit = $exam->event->first() ? $exam->event->first()->id : -1;

        $eventsData = [];

        foreach ($events as $event) {
            $eventInfo = $event->event_info();
            $date = '';

            if (isset($eventInfo['inclass']['dates']['text'])) {
                $date = $eventInfo['inclass']['dates']['text'];
            }

            $eventsData[$event->id] = trim($event->htmlTitle . ' ' . $date);
        }

        $liveResults = [];
        $syncDatas = ExamSyncData::where('exam_id', $exam->id)->get();

        [$results,$averageHour,$averageScore] = $exam->getResults();

        if (count($results) < count($syncDatas) || count($results) == 0) {
            $questions = json_decode($exam->questions, true);
            foreach ($syncDatas as $syncData) {
                //dd($questions);
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

                $start_at = explode('T', $syncData->started_at);
                $finish_at = explode(' ', $syncData->finish_at);

                /*$liveResults[] = array('id'=>$syncData->id,'name'=>$syncData->student->firstname . ' ' . $syncData->student->lastname,
                'answered' =>  $answered . ' / ' . count($allAnswers), 'correct' => $correct . '/' . $answered  , 'started_at'=> $start_at[1],'finish_at' => $finish_at[1]) ;*/

                $liveResults[] = ['id'=>$syncData->id, 'name'=>$syncData->student->firstname . ' ' . $syncData->student->lastname,
                    'answered' =>  $answered, 'correct' => $correct, 'totalAnswers' => count($allAnswers), 'started_at'=> $start_at[1], 'finish_at' => $finish_at[1]];
            }
        }

        return view('admin.exams.create', ['user' => $user, 'events' => $eventsData, 'edit' => $edit, 'exam' => $exam, 'event_id'=>$event_edit,
            'results' => $results, 'averageHour' => $averageHour, 'averageScore' => $averageScore, 'liveResults' => $liveResults]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function update(ExamRequest $request, Exam $exam)
    {
        $input = $request->all();

        $input['indicate_crt_incrt_answers'] = isset($input['indicate_crt_incrt_answers']) && $input['indicate_crt_incrt_answers'] == 1 ? 1 : 0;
        $input['random_questions'] = isset($input['random_questions']) && $input['random_questions'] == 1 ? 1 : 0;
        $input['display_crt_answers'] = isset($input['display_crt_answers']) && $input['display_crt_answers'] == 1 ? 1 : 0;
        $input['random_answers'] = isset($input['random_answers']) && $input['random_answers'] == 1 ? 1 : 0;

        $input['status'] = $request->status && $request->status = 'on' ? true : false;
        $input['publish_time'] = date('Y-m-d H:i', strtotime($request->publish_time));
        $exam->update($input);
        $exam->event()->detach();
        $exam->event()->attach($request->event_id);

        return redirect('admin/exams/' . $exam->id . '/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exam $exam)
    {
        if (!$exam->results->isEmpty()) {
            return redirect()->route('exams.index')->withErrors(__('This exam has items attached and can\'t be deleted.'));
        }

        $exam->delete();

        return redirect()->route('exams.index')->withStatus(__('Exam successfully deleted.'));
    }

    public function addQuestion(Request $request, Exam $exam)
    {
        $questions = json_decode($exam->questions) ? json_decode($exam->questions, true) : [];

        $questions[] = $request->question;
        $newQ = [];
        foreach ($questions as $key1 => $question) {
            $question['question'] = trim(str_replace(['"', "'"], '', $question['question']));
            $question['question'] = preg_replace('~^\s+|\s+$~us', '\1', $question['question']);

            foreach ($question['answers'] as $key => $answer) {
                $answer = str_replace(['"', "'"], '', $answer);
                $question['answers'][$key] = preg_replace('~^\s+|\s+$~us', '\1', $answer);
            }

            $newQ[] = $question;
        }

        $exam->questions = json_encode($newQ);
        $exam->save();

        return response()->json([
            'questions' => $exam->questions,
        ]);
    }

    public function deleteQuestion(Request $request, Exam $exam)
    {
        $questions = json_decode($exam->questions) ? json_decode($exam->questions, true) : [];

        if (isset($questions[$request->question])) {
            unset($questions[$request->question]);
        }

        $exam->questions = json_encode($questions);
        $exam->save();
    }

    public function updateQuestion(Request $request, Exam $exam)
    {
        $oldQuestions = json_decode($exam->questions, true);
        //dd($oldQuestion);

        $question = $request->question;
        $question['question'] = trim(str_replace(['"', "'"], '', html_entity_decode($request->question['question'])));

        foreach ($request->question['answers'] as $key => $answer) {
            $question['answers'][$key] = trim(str_replace(['"', "'"], '', html_entity_decode($answer)));
        }

        $oldQuestions[$request->key] = $question;

        $exam->questions = json_encode($oldQuestions);
        $exam->save();

        return response()->json([
            'questions' => $exam->questions,
        ]);
    }

    public function orderQuestion(Request $request, Exam $exam)
    {
        $oldQuestions = json_decode($exam->questions, true);
        $questionsNew = $request->questions;

        $sortedQuestions = [];

        ksort($questionsNew);

        foreach ($questionsNew as $key => $question) {
            $sortedQuestions[] = $oldQuestions[$question];
        }

        $exam->questions = json_encode($sortedQuestions);
        $exam->save();
    }

    public function cloneExam(Exam $exam)
    {
        $exam = $exam->replicate();

        $exam->status = false;
        $exam->push();

        return redirect()->route('exams.edit', $exam->id)->withStatus(__('Exam successfully cloned.'));
    }

    public function getLiveResults(Exam $exam)
    {
        $liveResults = [];
        $syncDatas = ExamSyncData::where('exam_id', $exam->id)->get();

        $questions = json_decode($exam->questions, true);
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

            $start_at = explode('T', $syncData->started_at);
            $finish_at = explode(' ', $syncData->finish_at);

            /*$liveResults[] = array('id'=>$syncData->id,'name'=>$syncData->student->firstname . ' ' . $syncData->student->lastname,
                'answered' =>  $answered . ' / ' . count($allAnswers), 'correct' => $correct . '/' . $answered  , 'started_at'=> $start_at[1],'finish_at' => $finish_at[1]) ;*/

            $liveResults[] = ['id'=>$syncData->id, 'name'=>$syncData->student->firstname . ' ' . $syncData->student->lastname,
                'answered' =>  $answered, 'correct' => $correct, 'started_at'=> $start_at[1], 'finish_at' => $finish_at[1], 'totalAnswers' => count($allAnswers)];
        }

        [$results,$averageHour,$averageScore] = $exam->getResults();

        return response()->json([
            'success'=>true,
            'liveResults' => $liveResults,
            'results' => $results,
            'averageHour' => $averageHour,
            'averageScore' => $averageScore,
        ]);
    }

    public function importFromFile(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors('Upload a valid csv file!');
        }

        $file = $request->file('file');

        if ($file) {
            $filename = $file->getClientOriginalName();
            $tempPath = $file->getRealPath();
            //dd($tempPath);
            $extension = explode('.', $filename)[1];

            $path = $request->file('file')->storeAs('import', $filename, 'storage');

            //dd($path);

            $spreadsheet = new Spreadsheet();
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile(storage_path('app/' . $path));
            $reader->setReadDataOnly(true);
            $file = $reader->load(storage_path('app/' . $path));
            $file = $file->getActiveSheet();

            $file = $file->toArray();

            $questions = [];

            foreach ($file as $key =>  $line) {
                if ($key == 0 || !$line[1]) {
                    continue;
                }

                $qInsert = trim(str_replace(['"', "'"], '', $line[1]));
                $qInsert = preg_replace('~^\s+|\s+$~us', '\1', $qInsert);

                $answer1 = str_replace(['"', "'"], '', $line[2]);
                $answer1 = preg_replace('~^\s+|\s+$~us', '\1', $answer1);

                $answer2 = str_replace(['"', "'"], '', $line[3]);
                $answer2 = preg_replace('~^\s+|\s+$~us', '\1', $answer2);

                $answer3 = str_replace(['"', "'"], '', $line[4]);
                $answer3 = preg_replace('~^\s+|\s+$~us', '\1', $answer3);

                $answer4 = str_replace(['"', "'"], '', $line[5]);
                $answer4 = preg_replace('~^\s+|\s+$~us', '\1', $answer4);

                $questions[] = ['question' => trim($qInsert), 'answer-credit' => 1,
                    'answers' => [trim($answer1), trim($answer2), trim($answer3), trim($answer4)],
                    'question-type' => 'radio buttons',
                    'correct_answer' => [trim($answer2)],
                ];
            }

            $exam = Exam::find($request->id);
            $exam->questions = json_encode($questions);
            $exam->save();
        }

        return \Redirect::route('exams.edit', ['exam' => $request->id, 'from_import' => 'finish']);
    }
}
