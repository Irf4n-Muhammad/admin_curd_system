<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\Project;
use DataTables;
 
class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::query();
        return DataTables::of($projects)
            ->addColumn('action', function ($project) {
                 
                $showBtn =  '<button ' .
                                ' type="button" ' .
                                ' class="btn btn-default fas fa-search-plus fa-lg" ' .
                                ' style="color: #3dd156" ' .
                                ' onclick="showProject(' . $project->id . ')">' .
                            '</button> ';
            
                $editBtn =  '<button ' .
                                ' type="button" ' .
                                ' class="btn btn-default fas fa-edit fa-lg" ' .
                                ' style="color: #1357cd" ' .
                                ' onclick="editProject(' . $project->id . ')">' .
                            '</button> ';
 
                $deleteBtn =  '<button ' .
                                ' type="button" ' .
                                ' class="btn btn-default fas fa-times-square fa-lg" ' .
                                ' style="color: #f02c0a" ' .
                                ' data-target="#deleteModal" ' .
                                ' data-toggle="modal" ' .
                                ' onclick="destroyProject(' . $project->id . ')">' .
                            '</button> ';
 
                return $showBtn . $editBtn . $deleteBtn;
            })
            ->rawColumns(
            [
                'action',
            ])
            ->make(true);
    }
 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|max:255',
            'description' => 'required',
        ]);
  
        $project = new Project();
        $project->name = $request->name;
        $project->description = $request->description;
        $project->save();
        return response()->json(['status' => "success"]);
    }
 
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::find($id);
        return response()->json(['project' => $project]);
    }
 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        request()->validate([
            'name' => 'required|max:255',
            'description' => 'required',
        ]);
  
        $project = Project::find($id);
        $project->name = $request->name;
        $project->description = $request->description;
        $project->save();
        return response()->json(['status' => "success"]);
    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Project::destroy($id);
        return response()->json(['status' => "success"]);
    }
}