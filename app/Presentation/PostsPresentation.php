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

    public function edit(Request $request): Response
    {
        $method = $request->method();

        if ($method === "GET") {
            $post_id = $request->get('post');
            $post = Post::getPostBySlugOrUUID($post_id);
            if ($post['status'] === 'success') {
                return json(200, $post);
            }
            return json(400, $post);
        }

        if ($method === "POST") {
            $post_id = $request->input('_id');
            $post_id = $post_id ?? $request->input('slug');
            $post = Post::getPostBySlugOrUUID($post_id);
            if ($post['status'] !== 'success') {
                return json(400, $post);
            }
            $post = $post['data']['post'];
            $updatable_attributes = ['authorName', 'slug', 'image', 'content'];
            $update_properties = [];
            foreach ($updatable_attributes as $attribute) {
                    $update_properties[$attribute] = $request->input($attribute);
            }
            if (
                isset($update_properties['image']) &&
                empty(trim($update_properties['image'])) &&
                isset($update_properties['content']) &&
                empty(trim($update_properties['content']))
            ) {
                return json(
                    400,
                    [
                        'status' => 'fail',
                        'data' => ['post' => trans('You cannot leave the image and content empty at the same time.')]
                    ]
                );
            }
            $updated_post = $post->update($update_properties);
            if ($updated_post['status'] === 'success') {
                return json(201, $updated_post);
            }
            return json(400, $updated_post);
        }
    }

    public function delete(Request $request): Response
    {
        $post_id = $request->input('post');
        if (empty(trim($post_id))) {
            return json(
                400,
                [
                    'status' => 'fail',
                    'data' => ['message' => 'The post id cannot be empty.']
                ]
            );
        }
        $deleted_post = Post::delete($post_id);
        if ($deleted_post['status'] === 'success') {
            return json(201, $deleted_post);
        }
        return json(400, $deleted_post);
    }
}
