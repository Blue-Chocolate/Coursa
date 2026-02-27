<?php 

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('Admin Panel Access', function () {

   it('admin can access the admin panel', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->get('/admin');
    
    // Filament redirects /admin → /admin/dashboard
    expect($response->status())->toBeIn([200, 302]);
    if ($response->status() === 302) {
        $this->actingAs($admin)
            ->get($response->headers->get('Location'))
            ->assertOk();
    }
});

    it('non-admin cannot access the admin panel', function () {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    });

    it('guest is redirected to admin login', function () {
        $this->get('/admin')
            ->assertRedirect('/admin/login');
    });
});