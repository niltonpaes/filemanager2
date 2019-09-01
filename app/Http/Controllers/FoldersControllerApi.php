<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Folder;
use App\User;

class FoldersControllerApi extends Controller
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

    public function index()
    {
        $user = request()->user();

        $folders = $user->folders;

        return $folders;
    }

    public function update(Request $request)
    {
        if (strlen(trim($request->foldername)) < 3) {
            return response(json_encode(array("message" => "Folder name invalid (<3)")), 503)
                ->header('Content-Type', 'text/plain');
        }

        $folder = Folder::find($request->id);
        if (!$folder) {
            return response(json_encode(array("message" => "Folder not found")), 503)
                ->header('Content-Type', 'text/plain');
        }

        if ($folder->user_id != auth()->id()) {
            return response(json_encode(array("message" => "You don't have access to this folder")), 403)
                ->header('Content-Type', 'text/plain');
        }

        $folder->foldername = $request->foldername;
        $folder->save();

        return $folder;
    }

    public function store(Request $request)
    {
        if (strlen(trim($request->foldername)) < 3) {
            return response(json_encode(array("message" => "Folder name invalid (<3)")), 503)
                ->header('Content-Type', 'text/plain');
        }

        $folder = new Folder;
        $folder->foldername = $request->foldername;
        $folder->user_id = auth()->id();;

        $folder->save();

        return $folder;
    }

    public function destroy(Request $request)
    {
        $folder = Folder::find($request->id);
        if (!$folder) {
            return response(json_encode(array("message" => "Folder not found")), 503)
                ->header('Content-Type', 'text/plain');
        }

        if ($folder->user_id != auth()->id()) {
            return response(json_encode(array("message" => "You don't have access to this folder")), 403)
                ->header('Content-Type', 'text/plain');
        }

        $folder->files()->delete();
        $folder->delete();

        return response(json_encode(array("message" => "OK")), 200)
            ->header('Content-Type', 'text/plain');
    }
}
