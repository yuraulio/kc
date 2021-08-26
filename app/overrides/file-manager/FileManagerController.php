<?php

namespace Alexusmai\LaravelFileManager\Controllers;

use Alexusmai\LaravelFileManager\Events\BeforeInitialization;
use Alexusmai\LaravelFileManager\Events\Deleting;
use Alexusmai\LaravelFileManager\Events\DirectoryCreated;
use Alexusmai\LaravelFileManager\Events\DirectoryCreating;
use Alexusmai\LaravelFileManager\Events\DiskSelected;
use Alexusmai\LaravelFileManager\Events\Download;
use Alexusmai\LaravelFileManager\Events\FileCreated;
use Alexusmai\LaravelFileManager\Events\FileCreating;
use Alexusmai\LaravelFileManager\Events\FilesUploaded;
use Alexusmai\LaravelFileManager\Events\FilesUploading;
use Alexusmai\LaravelFileManager\Events\FileUpdate;
use Alexusmai\LaravelFileManager\Events\Paste;
use Alexusmai\LaravelFileManager\Events\Rename;
use Alexusmai\LaravelFileManager\Events\Zip as ZipEvent;
use Alexusmai\LaravelFileManager\Events\Unzip as UnzipEvent;
use Alexusmai\LaravelFileManager\Requests\RequestValidator;
use Alexusmai\LaravelFileManager\FileManager;
use Alexusmai\LaravelFileManager\Services\Zip;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Model\Image_alt;
use App\Model\Media;

class FileManagerController extends Controller
{
    /**
     * @var FileManager
     */
    public $fm;

    /**
     * FileManagerController constructor.
     *
     * @param FileManager $fm
     */
    public function __construct(FileManager $fm)
    {
        $this->fm = $fm;
    }

    /**
     * Initialize file manager
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function initialize()
    {
        event(new BeforeInitialization());

        return response()->json(
            $this->fm->initialize()
        );
    }

    /**
     * Get files and directories for the selected path and disk
     *
     * @param RequestValidator $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function content(RequestValidator $request)
    {
        return response()->json(
            $this->fm->content(
                $request->input('disk'),
                $request->input('path')
            )
        );
    }

    /**
     * Directory tree
     *
     * @param RequestValidator $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tree(RequestValidator $request)
    {
        //dd($request->input('disk'));
        return response()->json(
            $this->fm->tree(
                $request->input('disk'),
                $request->input('path')
            )
        );
    }



    public function search(Request $request)
    {
        $param = $request->param;

        $folders = Storage::disk('public')->allDirectories();
        foreach($folders as $key => $folder){

            $path = Storage::disk('public')->allFiles($folder);

            $arr = [];
            if(count($path) != 0){
                //dd($path);
                foreach($path as $key1 => $folder1){
                    $name = substr($folder1, strrpos($folder1, '/') + 1);
                    //dd($name);
                    $second = explode("/", $folder1, 10);
                    $length = count($second);
                    $word = '';
                    foreach($second as $key11 => $val){
                        //dd($key11.'//'.$val[$key11]);
                        if($key11 != $length - 1){
                            $word = $word.'/'.$val;
                        }
                        $arr['path'] = $word;
                        $arr['name'] = $val;
                        $arr['size'] = formatBytes(Storage::disk('public')->size($folder1));

                        $arr['modified'] = date("d/m/Y", Storage::disk('public')->lastModified($folder1));
                        $arr['type'] = explode('.',$name)[1];
                    }
                    $files[$key][$key1] = $arr;
                }
            }
        }

        // $found = false;
        $result = [];

        foreach($files as $key => $valuee){
            foreach($valuee as $key1 => $valueee){
                //dd($valueee);
                // if($valuee[$key1]['name'] == $param){
                //     $found = true;
                // }
                if(strpos($valuee[$key1]['name'], $param) !== false){
                    //dd($valuee[$key1]['name']);
                    $result[$key1] = $valuee[$key1];
                }
            }
        }
        //dd($found);
        //dd($result);

        return response()->json([
            'success' => __('Search successfully.'),
            'data' => $result,
        ]);

    }

    /**
     * Check the selected disk
     *
     * @param RequestValidator $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function selectDisk(RequestValidator $request)
    {
        event(new DiskSelected($request->input('disk')));

        return response()->json([
            'result' => [
                'status'  => 'success',
                'message' => 'diskSelected',
            ],
        ]);
    }

    /**
     * Upload files
     *
     * @param RequestValidator $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        //dd($request->file('files')[0]->getClientOriginalName());
        event(new FilesUploading($request));

        $uploadResponse = $this->fm->upload(
            $request->input('disk'),
            $request->input('path'),
            $request->file('files'),
            $request->input('overwrite'),
        );

        if($uploadResponse['result']['status'] == 'success'){
            $name = substr($request->file('files')[0]->getClientOriginalName(), 0,strrpos($request->file('files')[0]->getClientOriginalName(), '.'));

            $alt = new Image_alt();
            $alt->name = $name;
            $alt->alt = explode(',',$request->input('overwrite'))[1];

            $alt->save();

        }

        event(new FilesUploaded($request));

        return response()->json($uploadResponse);
    }

    /**
     * Delete files and folders
     *
     * @param RequestValidator $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(RequestValidator $request)
    {

        //dd($request->all());
        event(new Deleting($request));

        $deleteResponse = $this->fm->delete(
            $request->input('disk'),
            $request->input('items')
        );


        return response()->json($deleteResponse);
    }

    /**
     * Copy / Cut files and folders
     *
     * @param RequestValidator $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function paste(RequestValidator $request)
    {
        //dd($request->all());
        event(new Paste($request));

        return response()->json(
            $this->fm->paste(
                $request->input('disk'),
                $request->input('path'),
                $request->input('clipboard')
            )
        );
    }

    /**
     * Rename
     *
     * @param RequestValidator $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function rename(RequestValidator $request)
    {
        //dd($request->all());
        event(new Rename($request));

        //update all media rows on db
        if(!strstr($request->oldName, '/')){
            $split = explode('.',$request->newName);
            Media::where('original_name',$request->oldName)->update(['original_name' => $request->newName, 'name' => $split[0]]);

            $oldName = substr($request->oldName, 0,strrpos($request->oldName, '.'));
            $newName = substr($request->newName, 0,strrpos($request->newName, '.'));
            Image_alt::where('name',$oldName)->update(['name' => $newName]);

        }else{
            $oldName = substr($request->oldName, strrpos($request->oldName, '/') + 1);
            $newName = substr($request->newName, strrpos($request->newName, '/') + 1);
            $split_new = explode('.',$newName);
            $split_old = explode('.',$oldName);
            Media::where('original_name', $oldName)->update(['original_name' => $newName, 'name' => $split_new[0]]);

            Image_alt::where('name',$split_old[0])->update(['name' => $split_new[0]]);

        }




        return response()->json(

            ['data' => $this->fm->rename(
                $request->input('disk'),
                $request->input('newName'),
                $request->input('oldName')
            ),
            'old' => $request->input('oldName'),
            'new' => $request->input('newName'),
            ]
        );
    }

    /**
     * Download file
     *
     * @param RequestValidator $request
     *
     * @return mixed
     */
    public function download(RequestValidator $request)
    {
        event(new Download($request));

        return $this->fm->download(
            $request->input('disk'),
            $request->input('path')
        );
    }

    /**
     * Create thumbnails
     *
     * @param RequestValidator $request
     *
     * @return \Illuminate\Http\Response|mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function thumbnails(RequestValidator $request)
    {
        return $this->fm->thumbnails(
            $request->input('disk'),
            $request->input('path')
        );
    }

    /**
     * Image preview
     *
     * @param RequestValidator $request
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function preview(RequestValidator $request)
    {
        return $this->fm->preview(
            $request->input('disk'),
            $request->input('path')
        );
    }

    /**
     * File url
     *
     * @param RequestValidator $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function url(RequestValidator $request)
    {
        return response()->json(
            $this->fm->url(
                $request->input('disk'),
                $request->input('path')
            )
        );
    }

    /**
     * Create new directory
     *
     * @param RequestValidator $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createDirectory(RequestValidator $request)
    {
        event(new DirectoryCreating($request));

        $createDirectoryResponse = $this->fm->createDirectory(
            $request->input('disk'),
            $request->input('path'),
            $request->input('name')
        );

        if ($createDirectoryResponse['result']['status'] === 'success') {
            event(new DirectoryCreated($request));
        }

        return response()->json($createDirectoryResponse);
    }

    /**
     * Create new file
     *
     * @param RequestValidator $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createFile(RequestValidator $request)
    {
        event(new FileCreating($request));

        $createFileResponse = $this->fm->createFile(
            $request->input('disk'),
            $request->input('path'),
            $request->input('name')
        );

        if ($createFileResponse['result']['status'] === 'success') {
            event(new FileCreated($request));
        }

        return response()->json($createFileResponse);
    }

    /**
     * Update file
     *
     * @param RequestValidator $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateFile(RequestValidator $request)
    {
        //dd($request->all());
        event(new FileUpdate($request));

        return response()->json(
            $this->fm->updateFile(
                $request->input('disk'),
                $request->input('path'),
                $request->file('file')
            )
        );
    }

    /**
     * Stream file
     *
     * @param RequestValidator $request
     *
     * @return mixed
     */
    public function streamFile(RequestValidator $request)
    {
        return $this->fm->streamFile(
            $request->input('disk'),
            $request->input('path')
        );
    }

    /**
     * Create zip archive
     *
     * @param RequestValidator $request
     * @param Zip              $zip
     *
     * @return array
     */
    public function zip(RequestValidator $request, Zip $zip)
    {
        //dd($request->all());
        event(new ZipEvent($request));

        return $zip->create();
    }

    /**
     * Extract zip archive
     *
     * @param RequestValidator $request
     * @param Zip              $zip
     *
     * @return array
     */
    public function unzip(RequestValidator $request, Zip $zip)
    {
        event(new UnzipEvent($request));

        return $zip->extract();
    }

    /**
     * Integration with ckeditor 4
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ckeditor()
    {
        return view('file-manager::ckeditor');
    }

    /**
     * Integration with TinyMCE v4
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinymce()
    {
        return view('file-manager::tinymce');
    }

    /**
     * Integration with TinyMCE v5
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinymce5()
    {
        return view('file-manager::tinymce5');
    }

    /**
     * Integration with SummerNote
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function summernote()
    {
        return view('file-manager::summernote');
    }

    /**
     * Simple integration with input field
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function fmButton()
    {
        return view('file-manager::fmButton');
    }

    public function fetchAlt(Request $request)
    {
        $name = $request->media_name;
        if(strpos($name, '.')){
            $name = substr($name, 0,strrpos($name, '.'));
        }

        $alt = Image_alt::where('name', $name)->first();


        return response()->json([
            'success' => __('Alt text is here!!'),
            'data' => $alt,
        ]);
    }

    public function saveAlt(Request $request)
    {
        //dd($request->all());
        if($request->id != 0){
            $alt = Image_alt::where('id',$request->id)->update(['alt' => $request->alt]);
            //dd($alt->id);
            $alt = Image_alt::find($request->id);
        }else{
            $alt = new Image_alt;

            $alt->name = $request->name;
            $alt->alt = $request->alt;

            $alt->save();

            $alt = Image_alt::find($alt->id);
        }

        return response()->json([
            'success' => __('Alt text successfull updated.'),
            'data' => $alt,
        ]);
    }
}
