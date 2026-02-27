<?php

namespace App\Livewire\Course;

use App\Actions\EnrollUserAction;
use App\Models\Course;
use Livewire\Component;

class EnrollButton extends Component
{
    public Course $course;
    public bool   $isEnrolled;

    public function mount(Course $course, bool $isEnrolled): void
    {
        $this->course     = $course;
        $this->isEnrolled = $isEnrolled;
    }

    public function enroll(): void
    {
        if (! auth()->check()) {
            $this->redirect(route('login'));
            return;
        }

        try {
            app(EnrollUserAction::class)->execute(auth()->user(), $this->course);
            $this->isEnrolled = true;
            $this->dispatch('enrolled');
        } catch (\Exception $e) {
            $this->addError('enroll', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.course.enroll-button');
    }
}