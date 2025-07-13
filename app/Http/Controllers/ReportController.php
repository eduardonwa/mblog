<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\CustomComment;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reportable_id' => 'required|integer',
            'reportable_type' => 'required|string|in:post,comment',
            'reason' => 'required|string',
            'message' => 'nullable|string|required_if:reason,other',
        ]);

        $modelClass = $request->reportable_type === 'post'
            ? Post::class 
            : CustomComment::class;

        $model = $modelClass::findOrFail($request->reportable_id);

        $model->reports()->create([
            'user_id' => Auth::id(),
            'reason' => $request->reason,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Thanks for submitting your report.');
    }
}
