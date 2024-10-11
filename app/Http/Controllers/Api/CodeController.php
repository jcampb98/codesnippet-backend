<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class CodeController extends Controller
{
    public function __construct() {
        $this->middleware("auth:api");
    }

    public function create(Request $request) {
        $request -> validate([
            'title' => $request -> title,
            'code_snippet' => $request -> code_snippet,
        ]);

        $code = Code::create([
            'title' => $request -> title,
            'code_snippet' => $request -> code_snippet,
            'user_id' => Auth::id(),
        ]);

        return response() -> json([
            'status' => 'success',
            'message' => 'Code snippet created successfully',
            'code' => $code,
        ]);
    }

    public function show($id) {
        $code = Code::find($id);

        if(!$code) {
            return response() -> json([
                'status' => 'error',
                'message' => 'Code snippet not found',
            ], 404);
        }

        return response() -> json([
            'status' => 'success',
            'code' => $code,
        ]);
    }

    public function update(Request $request, $id) {
        $request -> validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:255',
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