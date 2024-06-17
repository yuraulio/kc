<?php

namespace App\Imports;

use App\Jobs\FetchVimeoVideoDuration;
use App\Jobs\LessonUpdate;
use App\Model\Category;
use App\Model\Lesson;
use App\Model\Topic;
use App\Model\Type;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;

class LessonsImport implements OnEachRow, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function __construct(protected array $defaultLessonAttributes = [])
    {
        //
    }

    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $lesson = Lesson::create(array_merge($this->defaultLessonAttributes, [
            'status' => $row['published'],
            'bold' => $row['bold'],
            'title' => $row['title'],
            'vimeo_video' => $row['vimeo_video'],
            'links' => $this->prepareLinks($row),
        ]));

        dispatch(new FetchVimeoVideoDuration($lesson));

        $category = Category::where('name', 'like', $row['category'])->first();
        $topic = Topic::with('category')->whereTitle($row['topic'])->first();
        $category->updateLesson($topic, $lesson);

        $row['cateogry'] = $category->id;
        $row['topic_id'] = [$topic->id];

        dispatch(new LessonUpdate($row, $lesson));

        if ($row['type']) {
            $lesson->type()->attach([Type::whereName($row['type'])->first()]);
        }

        return $lesson;
    }

    public function rules(): array
    {
        return [
            'published' => 'boolean',
            'bold' => 'boolean',
            'title' => '',
            'type' => 'exists:types,name',
            'vimeo_video' => 'url',
            'category' => 'required',
            'topic' => 'required|exists:topics,title',
            'link_name_1' => '',
            'link_1' => '',
            'link_name_2' => '',
            'link_2' => '',
        ];
    }

    private function prepareLinks(array $row)
    {
        return json_encode(array_filter([
            array_filter([

                'name' => $row['link_name_1'],
                'link' => $row['link_1'],
            ]),
            array_filter([

                'name' => $row['link_name_2'],
                'link' => $row['link_2'],
            ]),
        ]));
    }
}
