<?php

namespace App\Livewire\Home;

use App\Models\Course;
use App\Models\Level;
use Livewire\Component;
use Livewire\WithPagination;

class CourseList extends Component
{
    use WithPagination;

    public string $search   = '';
    public string $levelId  = '';

    // Reset pagination when filters change
    public function updatedSearch(): void  { $this->resetPage(); }
    public function updatedLevelId(): void { $this->resetPage(); }

    public function render()
    {
        $courses = Course::published()
            ->with('level')
            ->withCount('lessons')
            ->when($this->search,  fn ($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->when($this->levelId, fn ($q) => $q->where('level_id', $this->levelId))
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        $levels = Level::orderBy('order')->get();

        return view('livewire.home.course-list', compact('courses', 'levels'));
    }
}