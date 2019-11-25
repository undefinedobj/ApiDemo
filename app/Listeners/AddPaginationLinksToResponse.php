<?php

namespace App\Listeners;

use Dingo\Api\Event\ResponseWasMorphed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddPaginationLinksToResponse
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * Morphing 和 Morphed 事件
     * 如果你需要控制响应数据如何被转化可以使用 Dingo 提供的 ResponseIsMorphing（转化前触发）
     * 和 ResponseWasMorphed（转化后触发）事件。
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ResponseWasMorphed $event)
    {
        if (isset($event->content['meta']['pagination'])) {
            $links = $event->content['meta']['pagination']['links'];
            $next = isset($links['next']) ? $links['next'] : null;
            $previous = isset($links['previous']) ? $links['previous'] : null;
            $event->response->headers->set(
                'link',
                sprintf('<%s>; rel="next", <%s>; rel="prev"', $next, $previous)
            );
        }
    }
}
