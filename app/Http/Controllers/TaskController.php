<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\TaskAttach;
use App\Models\Subtask;
use Yajra\DataTables\DataTables;

class TaskController extends Controller
{

    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824){
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576){
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024){
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1){
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1){
            $bytes = $bytes . ' byte';
        }
        else{
            $bytes = '0 bytes';
        }
        return $bytes;
    }


    public function dropzoneStore(Request $request)
    {
        $image = $request->file('file');
        $task_num = $request->route('taskNumber');
        //Getting the info of file
        $originalName = $request->file('file')->getClientOriginalName();
        $rawSize = $request->file('file')->getSize();

        $fileSize = $this->formatSizeUnits($rawSize);

        //Insert filename of file from
        $task_attach = new TaskAttach;
        $task_attach->task_attach_num = $task_num; // Store the task_num
        $task_attach->task_attach_name = $originalName;
        $task_attach->task_attach_filesize = $fileSize;


        if ($task_attach->save()) {
            //For Transferring of the file
            $image->move(public_path('attachments'), $originalName);
        }
        return response()->json(['success' => $originalName]);
    }

    public function showAttachTable(Request $request, $sub_taskNumber)
    {
        if ($request->ajax()) {

            $user = Auth::user();
            $name = $user->name;

            $param = request()->segment(2);

            $data = TaskAttach::select('task_attachment.*', 'task.*')
                ->join('task', 'task_attachment.task_attach_num', '=', 'task.task_num')
                ->where('task.task_num', $sub_taskNumber);

            return DataTables::of($data)->addIndexColumn()->make(true);
        }
    }

    //! Need to update that link must be not accessible through postman
    //
    public function showTask($taskNumber)
    {
        // Fetch the task with the given task number
        $task = Task::where('task_num', $taskNumber)->firstOrFail();

        // Pass the task to the view
        return view('view_task', compact('task'));
    }

    public function updateTaskTodo($id)
    {
        $task = Task::where('id', $id)->firstOrFail();
        $task->task_status = 'To-Do';
        $task->updated_at = now();
        $task->save();

        return response()->json(['success' => true]);
    }

    public function updateTaskInProgress($id)
    {
        $task = Task::where('id', $id)->firstOrFail();
        $task->task_status = 'In Progress';
        $task->updated_at = now();
        $task->save();

        return response()->json(['success' => true]);
    }


    public function updateTaskDone($id)
{
    $updatedRows = Task::select('sub_task.*', 'task.*')
            ->join('sub_task', 'task.task_num', '=', 'sub_task.sub_task_num')
            ->where('task.id', $id)
            ->update([
                'task.task_status' => 'Done',
                'task.updated_at' => now(),
                'sub_task.sub_task_status' => 'Done',
                'sub_task.updated_at' => now(),
            ]);

    if ($updatedRows > 0) {
        return response()->json(['success' => true]);
    } else {
        return response()->json(['success' => false], 404);
    }
}



    public function displayTask(Request $request)
    {
        $task_num = $request->input('task_num');

        $task = Task::where('task_num', $task_num)->first();

        if ($task) {
            return response()->json([
                'task_title' => $task->task_title,
                'task_desc' => $task->task_desc,
            ]);
        } else {
            return response()->json(['error' => 'Task not found'], 404);
        }
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {

            $user = Auth::user();
            $name = $user->name;

            $data = Task::select('*')
                ->where('task_user', $name);

            return DataTables::of($data)->addIndexColumn()->make(true);
        }
        return view('tasks');
    }

    public function generateControlNumber()
    {
        $year = date('Y') . '-';
        $month = date('m') . '-';
        $initial = sprintf('%06s', 1);
        $base_control = 'TASKNUM-';

        // Check if existing number in db
        $task_num = Task::max('task_num');

        if (empty($task_num) || !isset($task_num) || $task_num == NULL) {
            $task_number = $base_control . $year . $month . $initial;
        } else {
            // Split and increment
            $string = explode("-", $task_num);
            $incre_num = (int)$string[3] + 1;
            // Putting 6 leading zeroes
            $final_num = sprintf('%06s', $incre_num);
            $task_number = $base_control . $year . $month . $final_num;
        }

        return $task_number;
    }



    public function createTask(Request $request)
    {

        try {
            // Get the authenticated user
            $user = Auth::user();
            $name = $user->name;
            $status = 'To-Do';

            //Get Task Number
            $task_number = $this->generateControlNumber();

            $request->validate([
                'task_title' => 'required',
                'task_desc' => 'required',
                'sub_task_title' => 'required|array',
                'sub_task_desc' => 'required|array',
                'sub_task_title.*' => 'required',
                'sub_task_desc.*' => 'required',
            ]);

            $task = new Task;
            $task->task_num = $task_number;
            $task->task_user = $name;
            $task->task_status = $status;
            $task->task_title = $request->input('task_title');
            $task->task_desc = $request->input('task_desc');
            $task->save();

            $subTaskTitles = $request->input('sub_task_title');
            $subTaskDescs = $request->input('sub_task_desc');

            // Save sub tasks
            foreach ($subTaskTitles as $key => $subTaskTitle) {
                $subTaskDesc = $subTaskDescs[$key];

                $subTask = new Subtask;
                $subTask->sub_task_num = $task_number;
                $subTask->sub_task_status = $status;
                $subTask->sub_task_title = $subTaskTitle;
                $subTask->sub_task_desc = $subTaskDesc;
                $subTask->save();
            }

            return response()->json(['success' => true, 'message' => 'Task created successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function draftTask(Request $request)
    {

        try {
            // Get the authenticated user
            $user = Auth::user();
            $name = $user->name;
            $status = 'active';

            //Get Task Number
            $task_number = $this->generateControlNumber();

            $request->validate([
                'task_title' => 'required',
                'task_desc' => 'required',
                'sub_task_title' => 'required|array',
                'sub_task_desc' => 'required|array',
                'sub_task_title.*' => 'required',
                'sub_task_desc.*' => 'required',
            ]);

            $task = new Task;
            $task->task_num = $task_number;
            $task->task_user = $name;
            $task->task_status = "draft";
            $task->task_title = $request->input('task_title');
            $task->task_desc = $request->input('task_desc');
            $task->save();

            $subTaskTitles = $request->input('sub_task_title');
            $subTaskDescs = $request->input('sub_task_desc');

            // Save sub tasks
            foreach ($subTaskTitles as $key => $subTaskTitle) {
                $subTaskDesc = $subTaskDescs[$key];

                $subTask = new Subtask;
                $subTask->sub_task_num = $task_number;
                $subTask->sub_task_status = "draft";
                $subTask->sub_task_title = $subTaskTitle;
                $subTask->sub_task_desc = $subTaskDesc;
                $subTask->save();
            }

            return response()->json(['success' => true, 'message' => 'Task created successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    //Deleting Attachment
    public function deleteAttach($task_attach_name)
    {
        try {
            $task_attach = TaskAttach::where('task_attach_name', $task_attach_name)->firstOrFail();

            // Delete the file from the file system
            $filePath = public_path('attachments/'. $task_attach->task_attach_name);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Delete the row from the database
            $task_attach->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
