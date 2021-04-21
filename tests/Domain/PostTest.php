<?php

namespace tests\Domain;

use app\Domain\Post;
use app\Domain\ValueObjects\AuthorName;
use app\Domain\ValueObjects\Slug;
use DateTime;
use PHPUnit\Framework\TestCase;
use support\Db;

class PostTest extends TestCase
{
    public static Post $post;

    public static string $uuid = 'uuidwillbegeneratedautomatically';
    public static string $author_name = 'AndreCampos';
    public static string $image = '/public/files/newphoto.jpg';
    public static string $slug = 'create-a-post-for-do-the-tests';
    public static string $content = 'This post will be created to run all tests';
    public static int $views = 0;
    public static string $date = '2020-04-20T18:00:00';

    public static function setUpBeforeClass(): void
    {
        $uuid = self::$uuid;
        $author_name = new AuthorName(self::$author_name);
        $image = self::$image;
        $slug = new Slug(self::$slug);
        $content = self::$content;
        $views = self::$views;
        $now = new DateTime(self::$date);
        $now = $now->getTimestamp();
        $created = $now;
        $updated = $now;
        self::$post = new Post($uuid, $author_name, $slug, $image, $content, $views, $created, $updated);
    }

    public function testCreateNewPostWithErrors()
    {
        $post = Post::createNewPost('wrong name', self::$slug, self::$image, self::$content);
        $this->assertContains('fail', $post);
        $this->assertSame('The author name cannot have special characters.', $post['data']['authorName']);

        $post = Post::createNewPost(self::$author_name, 'wrong slug', self::$image, self::$content);
        $this->assertContains('fail', $post);
        $this->assertSame('The slug is not in the correct format.', $post['data']['slug']);
    }

    public function testCreateANewPostWithRightData()
    {
        $post = Post::createNewPost(self::$author_name, self::$slug, self::$image, self::$content);
        $this->assertSame('app\Domain\Post', get_class($post));
    }

    public function testSaveANewPostOnDB()
    {
        $post = Post::createNewPost(self::$author_name, self::$slug, self::$image, self::$content);
        $saved = $post->savePost();
        $this->assertContains('success', $saved);
        $this->assertSame('Post saved successfully.', $saved['data']['post']);
    }

    public function testSaveSamePostAgain()
    {
        $post = Post::createNewPost(self::$author_name, self::$slug, self::$image, self::$content);
        $post->savePost(); //first try to get data on DB
        $saved = $post->savePost();
        $this->assertContains('fail', $saved);
        $this->assertSame('Error, there is already a post with this slug or unique id.', $saved['data']['message']);
    }

    public function testGetPostBySlugOrUUIDWithWrongValue()
    {
        $post = Post::createNewPost(self::$author_name, self::$slug, self::$image, self::$content);
        $post->savePost(); //first try to get data on DB
        $post = Post::getPostBySlugOrUUID('wrongslugvalue');
        $this->assertContains('fail', $post);
        $this->assertSame('The post could not be found.', $post['data']['message']);
    }

    public function testgetPostBySlugOrUUIDWithRightSlug()
    {
        $post = Post::createNewPost(self::$author_name, self::$slug, self::$image, self::$content);
        $post->savePost(); //first try to get data on DB
        $post = Post::getPostBySlugOrUUID(self::$slug);
        $this->assertSame('app\Domain\Post', get_class($post));
    }

    public function testUpdateWithOutImageAndContent()
    {
        $post = Post::createNewPost(self::$author_name, self::$slug, self::$image, self::$content);
        $post->savePost();
        $update_properties = [
            'image' => '',
            'content' => ''
        ];
        $updated_post = $post->update($update_properties);
        $this->assertContains('fail', $updated_post);
        $this->assertSame('You cannot leave the image and content empty at the same time.', $updated_post['data']['post']);
    }

    public function testUpdateWithOutData()
    {
        $post = Post::createNewPost(self::$author_name, self::$slug, self::$image, self::$content);
        $post->savePost();
        $update_properties = [];
        $updated_post = $post->update($update_properties);
        $this->assertContains('fail', $updated_post);
        $this->assertSame('You need to modify at least 1 field to be able to update the post.', $updated_post['data']['post']);
    }

    public function testUpdateWithRightData()
    {
        $post = Post::createNewPost(self::$author_name, self::$slug, self::$image, self::$content);
        $post->savePost();
        $update_properties = [
            'content' => 'New content is a very good to renew the breath.'
        ];
        $updated_post = $post->update($update_properties);
        $this->assertContains('success', $updated_post);
        $this->assertSame('Post updated successfully.', $updated_post['data']['post']);
    }

    public function testDeleteWithWrongData()
    {
        $post = Post::createNewPost(self::$author_name, self::$slug, self::$image, self::$content);
        $post->savePost();
        $deleted = Post::delete('wrong slug or uuid');
        $this->assertContains('fail', $deleted);
        $this->assertSame('The post could not be found.', $deleted['data']['message']);
    }

    public function testDeleteWithRightData()
    {
        $post = Post::createNewPost(self::$author_name, self::$slug, self::$image, self::$content);
        $post->savePost();
        $deleted = Post::delete($post->uuid);
        $this->assertContains('success', $deleted);
        $this->assertSame('Post deleted successfully.', $deleted['data']['message']);
    }

    public function testInsertionOnPrivateProperties()
    {
        $this->expectErrorMessage('Cannot access private property app\Domain\Post::$uuid');
        self::$post->uuid = '12312312312';
    }

    public function testGetProperties()
    {
        $post = self::$post;

        $this->assertSame('app\Domain\Post', get_class($post));
        $this->assertSame($post->uuid, self::$uuid);
        $this->assertSame((string) $post->author_name, self::$author_name);
        $this->assertSame((string) $post->slug, self::$slug);
        $this->assertSame($post->image, self::$image);
        $this->assertSame($post->content, self::$content);
        $this->assertSame($post->views, self::$views);
        $date = new DateTime(self::$date);
        $date = $date->getTimestamp();
        $this->assertSame($post->created, $date);
    }

    protected function tearDown(): void
    {
        Db::connection('mongodb')->collection('test')->where('slug', self::$slug)->delete();
    }
}
