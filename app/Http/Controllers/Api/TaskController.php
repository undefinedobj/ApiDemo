<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Task;
use App\Transformers\TaskTransformer;
use Illuminate\Http\Request;

class TaskController extends ApiController
{
    /**
     * 资源集合响应(引入关联模型)
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        // 资源集合响应(引入关联模型)
        // $tasks = Task::all();
        // return $this->response->collection($tasks, new TaskTransformer());

        // 分页响应
        $tasks = Task::orderby('id', 'DESC')->paginate($request->per_page ?? config('api.perPage'));
        return $this->response->paginator($tasks, new TaskTransformer());
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);

        // 添加元数据
        //return $this->response->item($task, new TaskTransformer)->addMeta('meta_name', 'Savory');
        $meta = [
            'name' => 'Savory',
            'sex' => 'man',
            'age' => 18,
        ];
        return $this->response->item($task, new TaskTransformer())->setMeta($meta);

        // 设置响应状态码
        // return $this->response->item($task, new TaskTransformer)->setStatusCode(200);

        // 添加 Cookie
        /*$cookie = new \Symfony\Component\HttpFoundation\Cookie('name', 'Savory');
        return $this->response->item($task, new TaskTransformer())->withCookie($cookie);*/

        // 添加额外的响应头
        // return $this->response->item($task, new TaskTransformer)->withHeader('Foo', 'Bar');
        /*return $this->response->item($task, new TaskTransformer())->withHeaders([
            'Foo' => 'Bar',
            'Hello' => 'World'
        ]); // 一次添加多个响应头*/

        // 单个资源响应
        // return $this->response->item($task, new TaskTransformer());

        // 数组形式
        // return $this->response->array($task->toArray());
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
