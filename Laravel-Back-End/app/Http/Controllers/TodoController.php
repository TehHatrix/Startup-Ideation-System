<?php

namespace App\Http\Controllers;

use App\Events\TaskUpdated;
use App\Http\Resources\TodoResource;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($projectId)
    {
        // $task = Project::with('todos')->where('id', $projectId)->get();
        $project = Project::findOrFail($projectId);
        $task = $project->todos()->get();
        return response()->json([
            'tasks' => $task,
            'success' => true
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $projectId)
    {

        $validator = Validator::make($request->all(), [
            'task' => 'required|string',
            'assigned_to' => 'integer|nullable',
            'due_date' => 'nullable|date'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $data = $validator->validated();



        $project = Project::find($projectId);
        $task = new Todo;
        $task->task = $data['task'];
        $task->assigned_to = $data['assigned_to'];
        $task->due_date = $data['due_date'];
        $task->completed = false;
        $task->project()->associate($project);
        $task->save();


        

        // $task->project()->associate($projectId);

        // $task->project()->attach($projectId);

        broadcast(new TaskUpdated($projectId))->toOthers();

        return response()->json([
            'success' => true,
            'task' => $task,
            'errors' => null
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($project, $todo)
    {
        // $task = Todo::with('project')->where('id', $todo)->get();
        $task = Todo::findOrFail($todo)->where('project_id', $project)->get();
        return response()->json([
            'task' => $task,
            'success' => true
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $project, $id)
    {

        $validator = Validator::make($request->all(), [
            'task' => 'required|string',
            'assigned_to' => 'integer|nullable',
            'due_date' => 'nullable|date',
            'completed' => 'boolean'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $data = $validator->validated();


        $task = Todo::with('project')->where('id', $id)->update([
            'task' => $data['task'],
            'assigned_to' => $data['assigned_to'],
            'due_date' => $data['due_date'],
            'completed' => $data['completed'] ? $data['completed'] : false
        ]);

        broadcast(new TaskUpdated($project))->toOthers();


        return response()->json([
            'task' => $task,
            'success' => true,
            'errors' => null
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project, $id)
    {
        // $task = Todo::find($id)->where('project_id', $project)->delete();
        $task = Todo::find($id)->where('id', $id)->delete();
        broadcast(new TaskUpdated($project))->toOthers();

        return response()->json([
            'success' => true
        ], 200);
    }

    public function taskComplete(Request $request, $projectId, $id) {
        $validator = Validator::make($request->all(), [
            'completed' => 'required|boolean'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $data = $validator->validated();
        dd($data);

        $task = Todo::find($id)->update([
            'completed' => $data['completed']
        ]);
        
        broadcast(new TaskUpdated($projectId))->toOthers();

        return response()->json([
            'success' => true,
            'errors' => null
        ]);

    }

    


}
