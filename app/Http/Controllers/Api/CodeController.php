<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use App\Models\Code;

class CodeController extends Controller
{
    public function __construct() {
        $this->middleware(["auth:api", "throttle:60,1"]);
    }

    public function create(Request $request) {
        // Check if the user is authorized to create a code snippet
        $this->authorize('create', Code::class);

        $request -> validate([
            'title' => 'required|string|max:255', // Ensure title is required and a string and max length is 255
            'code_snippet' => 'required|string|max:500',
        ]);

        $code = Code::create([
            'title' => $request -> input('title'),
            'code_snippet' => $request -> input('code_snippet'),
            'user_id' => Auth::id(),
        ]);

        return response() -> json([
            'status' => 'success',
            'message' => 'Code snippet created successfully',
            'code' => $code,
        ]);
    }

    public function showAll($userId) {
        $this->authorize('showAll', Code::class);

        $codePosts = Code::where('user_id', $userId)->get();

        if($codePosts->isEmpty()) {
            return response() -> json([
                'status' => 'error',
                'message' => 'No code snippets found for this user',
            ], 404);
        }

        return response() -> json([
            'status' => 'success',
            'code' => $codePosts,
        ]);
    }

    public function update(Request $request, $id) {
        // Check if the user is authorized to update a code snippet
        $this->authorize('update', Code::class);

        $request -> validate([
            'title' => 'required|string|max:255',
            'code_snippet' => 'required|string|max:255',
        ]);

        $code = Code::find($id);

        if (!$code) {
            return response() -> json([
                'status' => 'error',
                'message' => 'Code snippet not found',
            ], 404);
        }

        $code -> title = $request -> title;
        $code -> code_snippet = $request -> code_snippet;
        $code -> save();

        return response() -> json([
            'status' => 'success',
            'message' => 'Code snippet updated successfully',
            'code' => $code,
        ]);
    }

    public function destroy($id) {
        // Check if the user is authorized to delete a code snippet
        $this->authorize('destroy', Code::class);

        $code = Code::find($id);

        if(!$code) {
            return response() -> json([
                'status' => 'error',
                'message' => 'Code snippet not found',
            ], 404);
        }

        $code -> delete();

        return response() -> json([
            'status' => 'success',
            'message' => 'Code snippet deleted successfully',
            'code' => $code,
        ]);
    }
}