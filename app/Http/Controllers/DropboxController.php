<?php

namespace App\Http\Controllers;

use App\Model\Dropbox;
use App\Model\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;

class DropboxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.aboveauthor')->except('cacheDropboxCLI', 'refreshDropBoxKey');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Dropbox  $dropbox
     * @return \Illuminate\Http\Response
     */
    public function show(Dropbox $dropbox)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Dropbox  $dropbox
     * @return \Illuminate\Http\Response
     */
    public function edit(Dropbox $dropbox)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Dropbox  $dropbox
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dropbox $dropbox)
    {
        $data = [];
        $currentuser = Auth::user();
        if($currentuser) {
            $uid = $currentuser->id;
            $data['blockcat'] = [];
            $data['groupblockcat'] = [];
            //$allbcats = Category::where('type', 0)->where('status', 1)->where('parent_id', 45)->where('admin_tpl', '!=', '0')->where('admin_tpl', '!=', '')->get();
            //$allbcats =

            $setting = Setting::where('key', 'DROPBOX_TOKEN')->firstOrFail();
            $authorizationToken = $setting->value;

            //'-z3uZ8uXMqEAAAAAAAG4UbziL-RWKtxNb_-bYu6YlyyA_9t9Tr1wAP45HRMGcVIx';
            $client = new \Spatie\Dropbox\Client($authorizationToken);
            $data['files'] = [];
            $data['folders'] = [];
            $folders0 = $client->listFolder('Courses Files');

            foreach ($folders0 as $key => $folders) {
                if(!is_array($folders)) {
                    continue;
                }
                foreach ($folders as $key => $row) {
                    $value = '';
                    if($row['.tag'] == 'folder') {
                        $di = $row['name'];
                        $value = $row['path_display'];
                    }

                    if($value != '' && strlen($value) > 3) {
                        $files = $client->listFolder($value); //, true) for recursive
                        usort($files['entries'], [$this, 'compareByName']);
                        foreach ($files['entries'] as $key => $row) {
                            $depth = 0;
                            if(isset($row['.tag']) && $row['.tag'] == 'file') :
                                $t = ''; //Carbon::parse($s2row['server_modified'])->format('d/m/Y H:i:s');
                                $data['files'][$di][$depth][] = ['dirname' => $row['path_display'], 'filename' => $row['name'], 'ext' => substr($row['name'], strrpos($row['name'], '.') + 1), 'last_mod' => $t];
                            elseif(isset($row['.tag']) && $row['.tag'] == 'folder') :
                                $data['folders'][$di][$depth][] = ['id' => $key, 'dirname' => $row['path_display'], 'foldername' => $row['name']];
                                $subdir = $row['path_display']; //$di .'/'.$row['basename'];
                                $subfiles = $client->listFolder($subdir);
                                usort($subfiles['entries'], [$this, 'compareByName']);

                                if(!empty($subfiles)) {
                                    //$depth = 1;
                                    foreach ($subfiles['entries'] as $skey => $srow) {
                                        $depth = 1;
                                        if($srow['.tag'] == 'file') {
                                            $st = Carbon::parse($srow['server_modified'])->format('d/m/Y H:i:s');
                                            $data['files'][$di][$depth][] = ['fid' => $key, 'dirname' => $srow['path_display'], 'filename' => $srow['name'], 'ext' => substr($srow['name'], strrpos($srow['name'], '.') + 1), 'last_mod' => $st];
                                        } elseif($srow['.tag'] == 'folder') {
                                            $data['folders'][$di][$depth][] = ['parent' => $key, 'id' => $skey, 'foldername' => $srow['name'], 'dirname' => $srow['path_display']];
                                            $subdir2 = $srow['path_display'];
                                            $subfiles2 = $client->listFolder($subdir2);

                                            if(!empty($subfiles2)) {
                                                //$depth = 2;
                                                usort($subfiles2['entries'], [$this, 'compareByName']);
                                                foreach ($subfiles2['entries'] as $s2key => $s2row) {
                                                    $depth = 2;
                                                    if($s2row['.tag'] == 'file') {
                                                        $sst = Carbon::parse($s2row['server_modified'])->format('d/m/Y H:i:s');
                                                        $data['files'][$di][$depth][] = ['parent' => $key, 'fid' => $skey, 'dirname' => $s2row['path_display'], 'filename' => $s2row['name'], 'ext' => substr($s2row['name'], strrpos($s2row['name'], '.') + 1), 'last_mod' => $sst];
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            endif;
                        }
                    }
                    $existing = false;
                    $existing = Dropbox::where('folder_name', $di)->first();
                    if($existing && isset($data['files'][$di]) && isset($data['folders'][$di])) {
                        $existing->update(['folder_name' => $di, 'files' => $data['files'][$di], 'folders' => $data['folders'][$di]]);
                    } elseif(isset($data['files'][$di]) && isset($data['folders'][$di])) {
                        $drop = new Dropbox;
                        $drop->folder_name = $di;
                        $drop->files = $data['files'][$di];
                        $drop->folders = $data['folders'][$di];
                        $drop->save();
                    } elseif(!isset($data['files'][$di]) && !$existing) {
                        $drop = new Dropbox;
                        $drop->folder_name = $di;
                        $drop->save();
                    }
                }
            }

            return response()->json([
                'success' => __('Dropbox folders and files successfully updated!'),
            ]);
        } else {
            return response()->json([
                'success' => __('Error while updating Dropbox folders and files!'),
            ]);
        }
    }

    private static function compareByName($a, $b)
    {
        return strnatcmp($a['name'], $b['name']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Dropbox  $dropbox
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dropbox $dropbox)
    {
        //
    }

    public function cacheDropboxCLI()
    {
        $data['blockcat'] = [];
        $data['groupblockcat'] = [];

        $setting = Setting::where('key', 'DROPBOX_TOKEN')->firstOrFail();
        $authorizationToken = $setting->value;

        $client = new \Spatie\Dropbox\Client($authorizationToken);
        $data['files'] = [];
        $data['folders'] = [];

        $folders0 = $client->listFolder('Courses Files');

        foreach ($folders0 as $key => $folders) {
            if(!is_array($folders)) {
                continue;
            }
            foreach ($folders as $key => $row) {
                $value = '';
                if($row['.tag'] == 'folder') {
                    $di = $row['name'];
                    $value = $row['path_display'];
                }

                if($value != '' && strlen($value) > 3) {
                    $files = $client->listFolder($value); //, true) for recursive
                    usort($files['entries'], [$this, 'compareByName']);
                    foreach ($files['entries'] as $key => $row) {
                        $depth = 0;
                        if(isset($row['.tag']) && $row['.tag'] == 'file') :
                            $t = ''; //Carbon::parse($s2row['server_modified'])->format('d/m/Y H:i:s');
                            $data['files'][$di][$depth][] = ['dirname' => $row['path_display'], 'filename' => $row['name'], 'ext' => substr($row['name'], strrpos($row['name'], '.') + 1), 'last_mod' => $t];
                        elseif(isset($row['.tag']) && $row['.tag'] == 'folder') :
                            $data['folders'][$di][$depth][] = ['id' => $key, 'dirname' => $row['path_display'], 'foldername' => $row['name']];
                            $subdir = $row['path_display']; //$di .'/'.$row['basename'];
                            $subfiles = $client->listFolder($subdir);
                            usort($subfiles['entries'], [$this, 'compareByName']);

                            if(!empty($subfiles)) {
                                //$depth = 1;
                                foreach ($subfiles['entries'] as $skey => $srow) {
                                    $depth = 1;
                                    if($srow['.tag'] == 'file') {
                                        $st = Carbon::parse($srow['server_modified'])->format('d/m/Y H:i:s');
                                        $data['files'][$di][$depth][] = ['fid' => $key, 'dirname' => $srow['path_display'], 'filename' => $srow['name'], 'ext' => substr($srow['name'], strrpos($srow['name'], '.') + 1), 'last_mod' => $st];
                                    } elseif($srow['.tag'] == 'folder') {
                                        $data['folders'][$di][$depth][] = ['parent' => $key, 'id' => $skey, 'foldername' => $srow['name'], 'dirname' => $srow['path_display']];
                                        $subdir2 = $srow['path_display'];
                                        $subfiles2 = $client->listFolder($subdir2);

                                        if(!empty($subfiles2)) {
                                            //$depth = 2;
                                            usort($subfiles2['entries'], [$this, 'compareByName']);
                                            foreach ($subfiles2['entries'] as $s2key => $s2row) {
                                                $depth = 2;
                                                if($s2row['.tag'] == 'file') {
                                                    $sst = Carbon::parse($s2row['server_modified'])->format('d/m/Y H:i:s');
                                                    $data['files'][$di][$depth][] = ['parent' => $key, 'fid' => $skey, 'dirname' => $s2row['path_display'], 'filename' => $s2row['name'], 'ext' => substr($s2row['name'], strrpos($s2row['name'], '.') + 1), 'last_mod' => $sst];
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        endif;
                    }
                }
                $existing = false;
                $existing = Dropbox::where('folder_name', $di)->first();
                if($existing && isset($data['files'][$di]) && isset($data['folders'][$di])) {
                    $existing->update(['folder_name' => $di, 'files' => $data['files'][$di], 'folders' => $data['folders'][$di]]);
                } elseif(isset($data['files'][$di]) && isset($data['folders'][$di])) {
                    $drop = new Dropbox;
                    $drop->folder_name = $di;
                    $drop->files = $data['files'][$di];
                    $drop->folders = $data['folders'][$di];
                    $drop->save();
                } elseif(!isset($data['files'][$di]) && !$existing) {
                    $drop = new Dropbox;
                    $drop->folder_name = $di;
                    $drop->save();
                }
            }
        }

        return response()->json([
            'success' => __('Dropbox folders and files successfully updated!'),
        ]);
    }

    public function refreshDropBoxKey()
    {
        update_dropbox_api();
    }
}
