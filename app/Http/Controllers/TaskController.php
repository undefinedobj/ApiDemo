<?php

namespace App\Http\Controllers;

use App\Task;
use App\Transformers\TaskTransformer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\JsonApiSerializer;

class TaskController extends Controller
{
    protected $resource;

    /**
     * TaskController constructor.
     */
    public function __construct()
    {
        $task = Task::findOrFail(1);

        $this->resource = new Item($task, function (Task $task) {
            return [
                'id' => $task->id,
                'text' => $task->text,
                'is_completed' => $task->is_completed ? 'yes' : 'no'
            ];
        });
    }

    /**
     * Fractal 默认支持 ArraySerializer、DataArraySerializer (默认)、JsonApiSerializer 三种序列化器
     *
     * DataArraySerializer (默认方式)
     *
     * @return string
     */
    public function data()
    {
        $fractal = new Manager();
        $fractal->setSerializer(new DataArraySerializer());  // DataArraySerializer (默认方式,即可以注释此行代码)

        return $fractal->createData($this->resource)->toJson();
    }

    /**
     * ArraySerializer 方式
     *
     * @return string
     */
    public function array()
    {
        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer());  // ArraySerializer

        return $fractal->createData($this->resource)->toJson();
    }

    /**
     * JsonApiSerializer 方式
     *
     * @return string
     */
    public function json()
    {
        $fractal = new Manager();
        $fractal->setSerializer(new JsonApiSerializer());    // JsonApiSerializer

        return $fractal->createData($this->resource)->toJson();
    }

    /**
     * 使用独立的转化器 transformer 类，以提高代码的可复用性
     *
     * 获取资源集合
     *
     * @return string
     */
    public function index()
    {
        $tasks = Task::all();
        $resources = new Collection($tasks, new TaskTransformer());
        $fractal = new Manager();

        // 未引入额外的数据(关联模型)
        // return $fractal->createData($resources)->toJson();

        // 引入额外的数据(关联模型),前提是需要设置好模型的关联关系
        return $fractal->parseIncludes('user')->createData($resources)->toJson();
    }

    /**
     * 使用独立的转化器 transformer 类，以提高代码的可复用性
     *
     * 获取单个资源
     *
     * @param int $id
     * @return string
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        $resource = new Item($task, new TaskTransformer());
        $fractal = new Manager();

        // 未引入额外的数据(关联模型)
        // return $fractal->createData($resource)->toJson();

        // 引入额外的数据(关联模型),前提是需要设置好模型的关联关系
        return $fractal->parseIncludes('user')->createData($resource)->toJson();
    }

    /**
     * 1.使用分页器
     *
     * 分页器可以提供丰富的分页结果信息，包括项目总数、上一页/下一页链接等，但相应的代价是可能会带来额外的性能开销
     * 比如每次调用都要统计项目总数，如果对性能要求比较苛刻，可以考虑使用游标来获取分页结果。
     *
     * @return string
     */
    public function paginator()
    {
        $paginator = Task::paginate();
        $tasks = $paginator->getCollection();

        $resource = new Collection($tasks, new TaskTransformer());
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        $fractal = new Manager();
        return $fractal->createData($resource)->toJson();
    }

    /**
     * 2.使用游标
     *
     * 通过游标获取分页结果类似限定查询，不会统计项目总数，在性能要优于分页器
     *
     * @param Request $request
     * @return string
     */
    public function cursor(Request $request)
    {
        $current = $request->input('current');
        $previous = $request->input('previous');
        $limit = $request->input('limit', 10);

        if ($current) {
            $tasks = Task::where('id', '>', $current)->take($limit)->get();
        } else {
            $tasks = Task::take($limit)->get();
        }

        $next = $tasks->last()->id;
        $cursor = new Cursor($current, $previous, $next, $tasks->count());

        $resource = new Collection($tasks, new TaskTransformer());
        $resource->setCursor($cursor);

        $fractal = new Manager();
        return $fractal->createData($resource)->toJson();
    }
}
