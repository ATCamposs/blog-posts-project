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

    public function add(Request $request): Response
    {
        $author_name = (string) $request->input('authorName');
        $slug = (string) $request->input('slug');
        $image = (string) $request->input('image');
        $content = (string) $request->input('content');
        if (empty(trim($image)) && empty(trim($content))) {
            return json(400, [
                'status' => 'fail',
                'data' => ['message' => trans('You cannot leave the image and content empty at the same time.')]
            ]);
        };
        $new_post = Post::createNewPost($author_name, $slug, $image, $content);
        if ($new_post['status'] !== 'success') {
            return json(400, $new_post);
        }
        $new_post = $new_post['data']['post'];
        $saved_post = $new_post->savePost();
        if ($saved_post['status'] === 'success') {
            return json(201, $saved_post);
        }
        return json(400, $saved_post);
    }
}
