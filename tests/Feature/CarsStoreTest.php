<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Cars;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CarsStoreTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use DatabaseMigrations;
    use DatabaseTransactions;
    use WithFaker;

    public function test_example(): void
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        Storage::fake('public');

        $file1 = UploadedFile::fake()->image('test1.jpg');
        $file2 = UploadedFile::fake()->image('test2.jpg');
        
        $data = [
            'description' => $this->faker->sentence,
            'financed' => $this->faker->boolean,
            'filenames' => [$file1, $file2],
        ];

        $response = $this->json('POST', '/car/store', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('cars', [
            'description' => $data['description'],
            'financed' => $data['financed'],
            'created_by' => $user->id,
        ]);
        Storage::disk('public')->assertExists('files/' . $file1->hashName());
        Storage::disk('public')->assertExists('files/' . $file2->hashName());
    }
}