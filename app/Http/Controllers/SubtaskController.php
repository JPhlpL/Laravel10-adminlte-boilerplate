<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SubtaskController extends Controller
{

    public function showSubTask(Request $request, $sub_taskNumber)
    {
        if ($request->ajax()) {

            $user = Auth::user();
            $name = $user->name;

            $param = request()->segment(2);

            $data = Subtask::select('sub_task.id AS suid', 'sub_task.*', 'task.*', 'sub_task.created_at AS sub_created_at', 'sub_task.updated_at AS sub_updated_at')
                ->join('task', 'sub_task.sub_task_num', '=', 'task.task_num')
                ->where('task.task_num', $sub_taskNumber);

            return DataTables::of($data)->addIndexColumn()->make(true);
        }
        return view('tasks');
    }

    public function updateSubTodo($id)
    {
        $task = Subtask::where('id', $id)->firstOrFail();
        $task->sub_task_status = 'To-Do';
        $task->updated_at = now();
        $task->save();

        return response()->json(['success' => true]);
    }

    public function updateSubInProgress($id)
    {
        $task = Subtask::where('id', $id)->firstOrFail();
        $task->sub_task_status = 'In Progress';
        $task->updated_at = now();
        $task->save();

        return response()->json(['success' => true]);
    }

    public function updateSubDone($id)
    {
        $task = Subtask::where('id', $id)->firstOrFail();
        $task->sub_task_status = 'Done';
        $task->updated_at = now();
        $task->save();

        return response()->json(['success' => true]);
    }
}
