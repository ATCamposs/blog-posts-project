<?php

declare(strict_types=1);

namespace app\Presentation;

use app\Domain\Post;
use support\Request;
use support\Response;

class PostsPresentation
{
    public function index(Request $request): Response
    {
        $limit = (int) $request->get('limit');
        $current_page = (int) $request->get('current_page');
        if (empty($limit) || empty($current_page)) {
            return json(400, [
                'status' => 'fail',
                'data' => ['message' => trans('Please fill in the current page and the item limit per page.')]
            ]);
        };
        $actual_posts_index = Post::indexPosts($limit, $current_page);
        if ($actual_posts_index['status'] === 'success') {
            return json(201, $actual_posts_index);
        }
        return json(400, $actual_posts_index);
    }
}
