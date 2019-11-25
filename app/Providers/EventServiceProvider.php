<?php

namespace App\Providers;

use App\Listeners\AddPaginationLinksToResponse;
use Dingo\Api\Event\ResponseWasMorphed;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],

        /**
         * Morphing 和 Morphed 事件
         *
         * 如果你需要控制响应数据如何被转化可以使用 Dingo 提供的 ResponseIsMorphing（转化前触发）
         * 和 ResponseWasMorphed（转化后触发）事件。
         * 结果见响应头的 link meta 部分
         */
        ResponseWasMorphed::class => [
            AddPaginationLinksToResponse::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
