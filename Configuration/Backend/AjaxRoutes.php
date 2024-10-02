<?php

return [
    'internal_news_notifies' => [
        'path' => '/internal-news/notifies',
        'target' => \Xima\XimaTypo3InternalNews\Controller\DateController::class . '::notifiesAction',
    ],
    'internal_news_detail' => [
        'path' => '/internal-news/detail',
        'target' => \Xima\XimaTypo3InternalNews\Controller\DateController::class . '::newsAction',
    ],
];
