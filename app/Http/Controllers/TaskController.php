<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
        * @OA\Get(
        *     path="/api/task",
        *     summary="Listado de Tareas",
        *     tags={"Listado de Tareas"},
        *     operationId="taskLikst",
        *     security={ {"passport": {}} },
        *     @OA\Response(
        *         response=200,
        *         description="Listado de tareas.",
        *         @OA\JsonContent()
        *     ),
        *     @OA\Response(
        *         response="default",
        *         description="Ha ocurrido un error.",
        *         @OA\JsonContent()
        *     ),
        *)
        */
        $tasks = Task::with('user')->paginate(15);
        return response()->json([$tasks], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
        * @OA\Post(
        *     path="/api/task",
        *     summary="Crear Tarea",
        *     tags={"Crear Tarea"},
        *     operationId="storeTask",
        *     security={ {"passport": {}} },
        *     @OA\Response(
        *         response=200,
        *         description="Crear Tarea.",
        *         @OA\JsonContent()
        *     ),
        *     @OA\Response(
        *         response="default",
        *         description="Ha ocurrido un error.",
        *         @OA\JsonContent()
        *     ),
        *   @OA\RequestBody(
        *       required=true,
        *       description="Campos para crear la tarea",
        *       @OA\JsonContent(
        *           required={"title","user_id"},
        *           @OA\Property(property="title", type="string", example="Tarea de prueba"),
        *           @OA\Property(property="description", type="string", example="Esta es la descripcion de la tarea de prueba"),
        *           @OA\Property(property="user_id", type="integer", example=1),
        *       ),
        *   )
        *)
        */
        $request->validate([
            'title' => 'required|string',
            'description' => 'string',
            'user_id' => 'required|exists:users,id'
        ]);
        try {
            DB::beginTransaction();
            $task = Task::create($request->all());
            DB::commit();
            return response()->json(["data" => $task, 'message' => "Tarea Creada con exito"], 201);
        }catch (\Exception $e) {
            //return error message
            DB::rollBack();
            return response()->json(['error' => ['message' => "Error al crear la tarea!", 'error' => $e]], 409);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        /**
        * @OA\Put(
        *     path="/api/task/{id}",
        *     summary="Editar Tarea",
        *     tags={"Editar Tarea"},
        *     operationId="storeTask",
        *     security={ {"passport": {}} },
        *     @OA\Parameter(
        *       name="id",
        *       in="path",
        *       required=true
        *      ),
        *     @OA\Response(
        *         response=200,
        *         description="Editar Tarea.",
        *         @OA\JsonContent()
        *     ),
        *     @OA\Response(
        *         response="default",
        *         description="Ha ocurrido un error.",
        *         @OA\JsonContent()
        *     ),
        *   @OA\RequestBody(
        *       required=true,
        *       description="Campos para editar la tarea",
        *       @OA\JsonContent(
        *           required={"title","user_id"},
        *           @OA\Property(property="title", type="string", example="Tarea de prueba"),
        *           @OA\Property(property="description", type="string", example="Esta es la descripcion de la tarea de prueba"),
        *           @OA\Property(property="user_id", type="integer", example=1),
        *       ),
        *   )
        *)
        */
        $request->validate([
            'title' => 'required|string',
            'description' => 'string',
            'user_id' => 'required|exists:users,id'
        ]);
        try {
            DB::beginTransaction();
            $task = Task::findOrFail($id);
            $task->fill($request->all());
            $task->save();
            DB::commit();
            return response()->json(["data" => $task, 'message' => "Tarea Actualizada con exito"], 201);
        }catch (\Exception $e) {
            //return error message
            DB::rollBack();
            return response()->json(['error' => ['message' => "Error al actualizar la tarea!", 'error' => $e]], 409);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /**
        * @OA\Delete(
        *     path="/api/task/{id}",
        *     summary="Eliminar Tarea",
        *     tags={"Eliminar Tarea"},
        *     operationId="storeTask",
        *     security={ {"passport": {}} },
        *     @OA\Parameter(
        *       name="id",
        *       in="path",
        *       required=true
        *      ),
        *     @OA\Response(
        *         response=200,
        *         description="Eliminar Tarea.",
        *         @OA\JsonContent()
        *     ),
        *     @OA\Response(
        *         response="default",
        *         description="Ha ocurrido un error.",
        *         @OA\JsonContent()
        *     ),
        *)
        */
        try {
            DB::beginTransaction();
            $task = Task::find($id);
            $task->delete();
            DB::commit();
            return response()->json(["data" => $task, 'message' => "Tarea Eliminada"], 200);
        } catch (\Exception $e) {
            //return error message
            DB::rollBack();
            return response()->json(['message' => "Error al eliminar la tarea!"], 409);
        }
    }
}
