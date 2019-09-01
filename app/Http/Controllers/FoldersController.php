<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Folder;
use App\User;

class FoldersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        $folders = $user->folders;

        return view('folders.index', compact('folders'));
    }

    public function create()
    {
        return view('folders.create');
    }

    public function store()
    {
        $attributes = request()->validate([
            'foldername' => ['required', 'min:3'],
        ]);

        $attributes['user_id'] = auth()->id();

        Folder::create($attributes);

        return redirect('/folders');
    }

    public function edit($id)
    {
        $folder = Folder::findorFail($id);

        if ($folder->user_id != auth()->id()) {
            abort(403);
        }

        return view('folders.edit', compact('folder'));
    }

    public function update($id)
    {
        $folder = Folder::findorFail($id);

        if ($folder->user_id != auth()->id()) {
            abort(403);
        }

        $attributes = request()->validate([
            'foldername' => ['required', 'min:3'],
        ]);

        $folder->update($attributes);

        return redirect('/folders');
    }

    public function destroy($id)
    {
        $folder = Folder::findorFail($id);

        if ($folder->user_id != auth()->id()) {
            abort(403);
        }

        $folder->files()->delete();
        $folder->delete();

        return redirect('/folders');
    }

    public function show(Folder $folder)
    {
        if ($folder->user_id != auth()->id()) {
            abort(403);
        }

        return view('folders.show', compact('folder'));
    }

    public function download(Folder $folder)
    {
        if ($folder->user_id != auth()->id()) {
            abort(403);
        }

        if ($folder->files->count() > 0) {

            $zip = new \ZipArchive();
            $filename = "../../download.zip";

            if ($zip->open($filename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
                exit("cannot open <$filename>\n");
            }

            foreach ($folder->files as $file) {
                $zip->addFromString($file->filename, $file->data);
            }

            $zip->close();

            return response()->download($filename, $folder->foldername . '.zip');
        } else {
            return back();
        }
    }
}
