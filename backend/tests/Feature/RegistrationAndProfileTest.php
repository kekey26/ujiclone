<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrationAndProfileTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('peminjam');
            $table->string('no_hp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto_profile')->nullable();
            $table->timestamps();
        });

        Schema::create('kategori', function (Blueprint $table): void {
            $table->id();
            $table->string('nama_kategori');
            $table->timestamps();
        });

        Schema::create('alat', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('kategori_id');
            $table->string('nama_alat');
            $table->integer('stok');
            $table->string('status_kondisi');
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function test_guest_can_register_as_a_borrower(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'New Borrower',
            'email' => 'borrower@example.test',
            'no_hp' => '08123456789',
            'alamat' => 'Bandung',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('peminjam.dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => 'New Borrower',
            'email' => 'borrower@example.test',
            'role' => 'peminjam',
        ]);
    }

    public function test_authenticated_user_can_view_and_update_profile(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Borrower',
            'email' => 'borrower@example.test',
            'password' => 'password123',
            'role' => 'peminjam',
        ]);

        $this->actingAs($user)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Profil Saya');

        $response = $this->put(route('profile.update'), [
            'name' => 'Updated Borrower',
            'email' => 'borrower@example.test',
            'no_hp' => '08123456789',
            'alamat' => 'Bandung',
            'foto_profile' => UploadedFile::fake()->createWithContent(
                'profile.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jC9sAAAAASUVORK5CYII=')
            ),
        ]);

        $response->assertRedirect(route('profile.edit'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Borrower',
            'no_hp' => '08123456789',
            'alamat' => 'Bandung',
        ]);
        $this->assertNotNull($user->fresh()->foto_profile);
        Storage::disk('public')->assertExists($user->fresh()->foto_profile);
    }

    public function test_admin_can_upload_and_view_equipment_image(): void
    {
        Storage::fake('public');

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => 'password123',
            'role' => 'admin',
        ]);
        $categoryId = Schema::getConnection()->table('kategori')->insertGetId([
            'nama_kategori' => 'Jaringan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.alat.store'), [
            'nama_alat' => 'Router',
            'kategori_id' => $categoryId,
            'stok' => 2,
            'status_kondisi' => 'Baik',
            'gambar' => UploadedFile::fake()->createWithContent(
                'router.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jC9sAAAAASUVORK5CYII=')
            ),
        ]);

        $response->assertRedirect(route('admin.alat.index'));
        $imagePath = Schema::getConnection()->table('alat')->value('gambar');
        $this->assertNotNull($imagePath);
        Storage::disk('public')->assertExists($imagePath);
        $this->assertSame('/storage/' . $imagePath, (new \App\Models\Alat(['gambar' => $imagePath]))->gambar_url);
    }
}
