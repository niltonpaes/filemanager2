<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Folder;
use App\File;
use App\User;

class FilesController extends Controller
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

    public function store(Request $request, Folder $folder)
    {
        $request->validate([
            'data' => "required",
            'description' => ['required', 'min:3']
        ]);

        $file = $request->file('data');

        $description = request('description');
        $filename = $file->getClientOriginalName();
        $mime_type = $file->getClientMimeType();
        $data = $file->openFile()->fread($file->getSize());

        $folder->files()->create([
            'description' => $description,
            'filename' => $filename,
            'mime_type' => $mime_type,
            'data' => $data
        ]);

        unlink($file->getPathName());

        return back();
    }

    public function destroy(File $file)
    {
        if ($file->folder->user_id != auth()->id()) {
            abort(403);
        }

        $file->delete();

        return back();
    }

    public function download(File $file)
    {
        if ($file->folder->user_id != auth()->id()) {
            abort(403);
        }

        return response()->make($file->data, 200, array(
            'Content-Type' => $file->mime_type,
            'Content-Disposition' => 'attachment; filename=' . $file->filename
        ));
    }
}
