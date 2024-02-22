<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleController extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_lists_articles_paginated()
    {
        // Arrange: Create some articles
        Article::factory()->count(10)->create();

        // Act: Make a GET request to the articles index route
        $response = $this->getJson(route('articles.index'));

        // Assert: Check the response structure and status
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'content', 'image', 'created_at', 'updated_at']
                ],
                'links',
            ]);
    }

    /** @test */
    public function it_shows_an_article_details()
    {
        // Arrange: Create an article
        $article = Article::create([
            'title' => 'Test Article',
            'content' => 'Test content',
            'image' => 'test_image.jpg',
        ]);

        // Act: Make a GET request to the show route
        $response = $this->getJson(route('articles.show', $article->id));

        // Assert: Check the response status and structure
        $response->assertStatus(200)
            ->assertJson([
                'id' => $article->id,
                'title' => 'Test Article',
                'content' => 'Test content',
                'image' => 'test_image.jpg',
            ]);
    }

    /** @test */
    public function it_stores_an_article_and_returns_it_as_json()
    {
        // Mock the Storage facade to prevent actual file operations
        Storage::fake('public');

        // Prepare a fake image file
        $file = UploadedFile::fake()->image('article.jpg');

        // Prepare the data to be submitted
        $data = [
            'title' => 'New Article Title',
            'content' => 'Content of the new article',
            'category' => 'news',
            'status' => 'published',
            'image' => $file, // Attach the fake file
        ];

        // Act: Send a POST request to the store route with the data
        $response = $this->postJson(route('articles.store'), $data);

        // Assert: Check if an article was created with the provided data
        $this->assertDatabaseHas('articles', [
            'title' => 'New Article Title',
            'content' => 'Content of the new article',
            'category' => 'news',
            'status' => 'published',
            'image' => $response->json('image'),
        ]);

        // Assert: The response status is 201 (Created) and the structure matches the expected JSON
        $response->assertStatus(201)
            ->assertJsonStructure([
                'title',
                'content',
                'category',
                'status',
                'image',
            ]);

        Storage::disk('public')->assertExists('images/articles/' . $response->json('image'));
    }

    /** @test */
    public function it_updates_an_article_and_returns_the_updated_version()
    {
        // Arrange: Create a sample article and mock the Storage facade
        Storage::fake('public');
        $article = Article::create([
            'title' => 'Original Title',
            'content' => 'Original content',
            'category' => 'news',
            'status' => 'published',
            'image' => 'original_image.jpg',
        ]);
        $newImage = UploadedFile::fake()->image('updated_image.jpg');

        // New data for the update
        $updateData = [
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'category' => 'stories',
            'status' => 'draft',
            'image' => $newImage,
        ];

        // Act: Send a PUT/PATCH request to the update route
        $response = $this->json('PUT', route('articles.update', $article->id), $updateData);

        // Assert: The article was updated in the database
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'category' => 'stories',
            'status' => 'draft',
        ]);

        // Assert: The response contains the updated article data and status is 200 OK
        $response->assertStatus(200)
            ->assertJson([
                'id' => $article->id,
                'title' => 'Updated Title',
                'content' => 'Updated content',
                'category' => 'stories',
                'status' => 'draft',
            ]);
    }
    /** @test */
    /** @test */
    public function it_deletes_an_article_and_its_image()
    {
        // Arrange: Create an article and mock the storage facade if necessary
        Storage::fake('public');
        $article = Article::create([
            'title' => 'Test Article',
            'content' => 'This is a test article',
            // Assuming 'image' stores the filename of the image in the articles table
            'image' => 'test_image.jpg',
        ]);
        // Manually create a file in the fake storage to simulate the article's image
        Storage::disk('public')->put('images/articles/test_image.jpg', 'dummy content');

        // Act: Send a DELETE request to the destroy route
        $response = $this->deleteJson(route('articles.destroy', $article->id));

        // Assert: The article is deleted from the database
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);

        // Assert: The article's image is deleted from storage
        Storage::fake('public')->assertMissing('images/articles/test_image.jpg');

        // Assert: The response status is 200 OK and contains a success message
        $response->assertStatus(200)->assertJson(['message' => 'Article deleted successfully']);
    }
}
