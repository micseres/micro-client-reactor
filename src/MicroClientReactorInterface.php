<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 28.09.18
 */

namespace Micseres\MicroClientReactor;

use Micseres\MicroClientReactor\Request\ClientRequest;

interface MicroClientReactorInterface
{
    public function sendAndReceive(ClientRequest $request): string;
}
