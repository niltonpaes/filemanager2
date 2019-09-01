<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Folder;
use App\File;
use App\User;

class FilesControllerApi extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $folder = Folder::find($request->folder_id);
        if (!$folder) {
            return response(json_encode(array("message" => "Folder not found")), 503)
                ->header('Content-Type', 'text/plain');
        }

        if ($folder->user_id != auth()->id()) {
            return response(json_encode(array("message" => "You don't have access to this folder")), 403)
                ->header('Content-Type', 'text/plain');
        }

        $files = File::where('folder_id', '=', $request->folder_id)->select('id', 'description', 'mime_type', 'created_at', 'updated_at')->get();

        return $files;
    }

    public function store(Request $request)
    {
        if (strlen(trim($request->description)) < 3) {
            return response(json_encode(array("message" => "File description invalid (<3)")), 503)
                ->header('Content-Type', 'text/plain');
        }

        if (!$request->file('data')) {
            return response(json_encode(array("message" => "File data invalid")), 503)
                ->header('Content-Type', 'text/plain');
        }

        $file = $request->file('data');

        $description = request('description');

        $filename = $file->getClientOriginalName();
        $mime_type = $file->getClientMimeType();
        $data = $file->openFile()->fread($file->getSize());

        $newFile = new File;
        $newFile->description = $description;
        $newFile->filename = $filename;
        $newFile->mime_type = $mime_type;
        $newFile->data = $data;
        $newFile->folder_id = request('folder_id');

        $newFile->save();

        unlink($file->getPathName());

        return response(json_encode(
            array(
                "id" => $newFile->id,
                "description" => $newFile->description,
                "mime_type" => $newFile->mime_type,
                "created_at" => date_format($newFile->created_at, "Y-m-d H:i:s"),
                "updated_at" => date_format($newFile->updated_at, "Y-m-d H:i:s")
            )
        ), 201)
            ->header('Content-Type', 'text/plain');
    }

    public function destroy(Request $request)
    {
        $file = File::find($request->id);
        if (!$file) {
            return response(json_encode(array("message" => "File not found")), 503)
                ->header('Content-Type', 'text/plain');
        }

        $folder = $file->folder;
        if ($folder->user_id != auth()->id()) {
            return response(json_encode(array("message" => "You don't have access to this file")), 403)
                ->header('Content-Type', 'text/plain');
        }

        $file->delete();

        return response(json_encode(array("message" => "OK")), 200)
            ->header('Content-Type', 'text/plain');
    }
}
