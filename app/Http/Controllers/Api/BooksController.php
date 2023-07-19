<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Books;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function getBook(Request $request)
    {
        try {
            if ($request->user('sanctum')) {
                $books = Books::where('user_id', auth('sanctum')->user()->id)->paginate($request->input('results', 10));
                return response()->json($books);
            }

            return response()->json([
                'success' => false,
                'message' => 'unathenticated'
            ], 401);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function add(Request $request)
    {
        try {
            if ($request->user('sanctum')) {
                $validator = Validator::make($request->all(), [
                    'isbn' => 'required',
                    'title' => 'required',
                    'subtitle' => 'required',
                    'author' => 'required',
                    'published' => 'required',
                    'publisher' => 'required',
                    'pages' => 'required|numeric',
                    'description' => 'required',
                    'website' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->first(),
                        'errors' => $validator->errors()
                    ], 422);
                }

                $input = $request->all();
                $input['user_id'] = auth('sanctum')->user()->id;
                $book = Books::create($input);

                return response()->json([
                    'success' => true,
                    'message' => 'book created',
                    'book' => $book
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'unathenticated'
            ], 401);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function edit(Request $request, $book_id)
    {
        try {
            if ($request->user('sanctum')) {
                $validator = Validator::make($request->all(), [
                    'isbn' => 'required',
                    'title' => 'required',
                    'subtitle' => 'required',
                    'author' => 'required',
                    'published' => 'required',
                    'publisher' => 'required',
                    'pages' => 'required|numeric',
                    'description' => 'required',
                    'website' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->first(),
                        'errors' => $validator->errors()
                    ], 422);
                }

                $input = $request->all();
                $book = Books::find($book_id);
                if ($book) {
                    if ($book['user_id'] == auth('sanctum')->user()->id) {
                        $book->update($input);

                        return response()->json([
                            'success' => true,
                            'message' => 'book updated',
                            'book' => $book
                        ]);
                    }
                    return response()->json([
                        'success' => false,
                        'message' => 'user not allowed to edit',
                    ], 403);
                }
                return response()->json([
                    'success' => false,
                    'message' => 'book not found',
                ], 404);
            }

            return response()->json([
                'success' => false,
                'message' => 'unathenticated'
            ], 401);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getBookById(Request $request, $book_id)
    {
        try {
            if ($request->user('sanctum')) {
                $book = Books::find($book_id);
                if ($book) {
                    if ($book['user_id'] == auth('sanctum')->user()->id) {
                        return response()->json([
                            'success' => true,
                            'book' => $book
                        ]);
                    }
                    return response()->json([
                        'success' => false,
                        'message' => 'user not allowed to get book',
                    ], 403);
                }
                return response()->json([
                    'success' => false,
                    'message' => 'book not found',
                ], 404);
            }

            return response()->json([
                'success' => false,
                'message' => 'unathenticated'
            ], 401);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request, $book_id)
    {
        try {
            if ($request->user('sanctum')) {
                $book = Books::find($book_id);
                if ($book) {
                    if ($book['user_id'] == auth('sanctum')->user()->id) {
                        $deleted_book = $book;
                        $book->delete();
                        return response()->json([
                            'success' => true,
                            'deleted_book' => $deleted_book
                        ]);
                    }
                    return response()->json([
                        'success' => false,
                        'message' => 'user not allowed to delete',
                    ], 403);
                }
                return response()->json([
                    'success' => false,
                    'message' => 'book not found',
                ], 404);
            }

            return response()->json([
                'success' => false,
                'message' => 'unathenticated'
            ], 401);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
