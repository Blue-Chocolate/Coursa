<?php 
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('Lesson Progress', function () {

    it('enrolled user can access a paid lesson', function () {
        $user   = User::factory()->create();
        $course = Course::factory()->create(['status' => true]);
        $lesson = Lesson::factory()->for($course)->create(['is_free_preview' => false]);

        Enrollment::factory()->create([
            'user_id'   => $user->id,
            'course_id' => $course->id,
        ]);

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->id}")
            ->assertOk();
    });

    it('guest can access a free preview lesson', function () {
        $course = Course::factory()->create(['status' => true]);
        $lesson = Lesson::factory()->for($course)->create(['is_free_preview' => true]);

        $this->get("/courses/{$course->slug}/lessons/{$lesson->id}")
            ->assertOk();
    });

    it('guest cannot access a paid lesson', function () {
        $course = Course::factory()->create(['status' => true]);
        $lesson = Lesson::factory()->for($course)->create(['is_free_preview' => false]);

        $this->get("/courses/{$course->slug}/lessons/{$lesson->id}")
            ->assertStatus(401);
    });

    it('non-enrolled user cannot access a paid lesson', function () {
        $user   = User::factory()->create();
        $course = Course::factory()->create(['status' => true]);
        $lesson = Lesson::factory()->for($course)->create(['is_free_preview' => false]);

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->id}")
            ->assertStatus(403);
    });

    it('marks a lesson as complete and records timestamp', function () {
        Mail::fake();

        $user   = User::factory()->create();
        $course = Course::factory()->create(['status' => true]);
        $lesson = Lesson::factory()->for($course)->create();

        Enrollment::factory()->create([
            'user_id'   => $user->id,
            'course_id' => $course->id,
        ]);

        LessonProgress::create([
            'user_id'   => $user->id,
            'lesson_id' => $lesson->id,
        ]);

        $this->actingAs($user)
            ->post("/lessons/{$lesson->id}/complete")
            ->assertSuccessful();

        $progress = LessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        expect($progress->completed_at)->not->toBeNull();
    });

    it('completing all lessons triggers course completion', function () {
        Mail::fake();

        $user    = User::factory()->create();
        $course  = Course::factory()->create(['status' => true]);
        $lessons = Lesson::factory()->count(3)->for($course)->create();

        Enrollment::factory()->create([
            'user_id'   => $user->id,
            'course_id' => $course->id,
        ]);

        foreach ($lessons as $lesson) {
            LessonProgress::create([
                'user_id'   => $user->id,
                'lesson_id' => $lesson->id,
            ]);
        }

        // Complete all lessons
        foreach ($lessons as $lesson) {
            $this->actingAs($user)
                ->post("/lessons/{$lesson->id}/complete");
        }

        $this->assertDatabaseHas('course_completions', [
            'user_id'   => $user->id,
            'course_id' => $course->id,
        ]);
    });

    it('no data leaks between users on same lesson', function () {
        $user1  = User::factory()->create();
        $user2  = User::factory()->create();
        $course = Course::factory()->create(['status' => true]);
        $lesson = Lesson::factory()->for($course)->create();

        LessonProgress::create([
            'user_id'      => $user1->id,
            'lesson_id'    => $lesson->id,
            'completed_at' => now(),
            'watch_seconds'=> 300,
        ]);

        LessonProgress::create([
            'user_id'      => $user2->id,
            'lesson_id'    => $lesson->id,
            'completed_at' => null,
            'watch_seconds'=> 0,
        ]);

        $progress1 = LessonProgress::where('user_id', $user1->id)->where('lesson_id', $lesson->id)->first();
        $progress2 = LessonProgress::where('user_id', $user2->id)->where('lesson_id', $lesson->id)->first();

        // user1's completion does not affect user2
        expect($progress1->completed_at)->not->toBeNull()
            ->and($progress2->completed_at)->toBeNull()
            ->and($progress1->watch_seconds)->toBe(300)
            ->and($progress2->watch_seconds)->toBe(0);
    });
});
