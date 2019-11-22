<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ArticleResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // 1.未关联模型的情况：
        /*return [
            'type' => 'article',
            'id'   => (string)$this->id,
            'attributes' => [
                'title' => $this->title,
                'content' => $this->body,
            ],
        ];*/

        /**
         * 2.关联关系的情况( Article & Comment )
         *
         * 如果想要自定义 comments 的返回字段可以去修改 ArticleCommentsResource 类
         *
         * API Resource 最大的优点是解耦了数据格式与业务代码的耦合，提高了代码的复用性和可维护性
         *
         * API Resource 文档 https://xueyuanjun.com/post/8223.html
         */
        return [
            'type' => 'article',
            'id'   => (string)$this->id,
            'attributes' => [
                'title' => $this->title,
                'content' => $this->body,
            ],
            'comments' => new ArticleCommentsResource($this->comments),
        ];
    }

    /**
     * 额外信息
     *
     * 有时候你想要获取数据库记录以外的其他相关数据，如分页信息或者页面 URL
     * 可以在 ArticleResource 类中新增 with 方法来实现
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function with($request)
    {
        return [
            'links'    => [
                'self' => url('api/articles/' . $this->id),
            ],
        ];
    }
}
