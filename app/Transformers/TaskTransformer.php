<?php

namespace App\Transformers;

use App\Task;
use League\Fractal\TransformerAbstract;

class TaskTransformer extends TransformerAbstract
{
    /**
     * 引入额外的数据(关联模型),前提是需要设置好模型的关联关系
     *
     * @var array
     */
    protected $availableIncludes = ['user'];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Task $task)
    {
        return [
            'id' => $task->id,
            'text' => $task->text,
            'completed' => $task->is_completed ? 'yes' : 'no',
            'link' => route('tasks.show', ['id' => $task->id])
        ];
    }

    /**
     * 引入额外的数据(关联模型),前提是需要设置好模型的关联关系
     *
     * @param Task $task
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Task $task)
    {
        $user = $task->user;
        return $this->item($user, new UserTransformer());
    }
}
