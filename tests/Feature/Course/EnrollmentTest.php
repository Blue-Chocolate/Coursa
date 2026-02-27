<?php 

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('Course Enrollment', function () {

    it('guest cannot enroll', function () {
        $course = Course::factory()->create(['is_published' => true]);

        $this->post("/courses/{$course->slug}/enroll")
            ->assertRedirect('/login');
    });

    it('authenticated user can enroll in a published course', function () {
        $user   = User::factory()->create();
        $course = Course::factory()->create(['is_published' => true]);

        $this->actingAs($user)
            ->post("/courses/{$course->slug}/enroll")
            ->assertSuccessful();

        $this->assertDatabaseHas('enrollments', [
            'user_id'   => $user->id,
            'course_id' => $course->id,
        ]);
    });

    it('user cannot enroll twice', function () {
        $user   = User::factory()->create();
        $course = Course::factory()->create(['is_published' => true]);

        $this->actingAs($user)->post("/courses/{$course->slug}/enroll");
        $this->actingAs($user)->post("/courses/{$course->slug}/enroll");

        expect(Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->count()
        )->toBe(1);
    });
});
