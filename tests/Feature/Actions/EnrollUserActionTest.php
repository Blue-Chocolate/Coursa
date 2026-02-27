<?php
use App\Actions\Enrollment\EnrollUserAction;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('EnrollUserAction', function () {

    it('enrolls a user in a course', function () {
        $user   = User::factory()->create();
        $course = Course::factory()->create();

        $action = new EnrollUserAction();
        $action->execute($user, $course);

        $this->assertDatabaseHas('enrollments', [
            'user_id'   => $user->id,
            'course_id' => $course->id,
        ]);
    });

    it('does not create duplicate enrollments', function () {
        $user   = User::factory()->create();
        $course = Course::factory()->create();

        $action = new EnrollUserAction();
        $action->execute($user, $course);
        $action->execute($user, $course); // second call

        expect(Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->count()
        )->toBe(1);
    });

    it('sets enrolled_at timestamp', function () {
        $user   = User::factory()->create();
        $course = Course::factory()->create();

        $action = new EnrollUserAction();
        $action->execute($user, $course);

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        expect($enrollment->enrolled_at)->not->toBeNull();
    });
});
