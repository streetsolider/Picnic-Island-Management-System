<?php

namespace App\Livewire\Visitor\BeachActivities;

use App\Models\BeachActivityCategory;
use App\Models\BeachService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

#[Layout('layouts.visitor')]
#[Title('Beach Activities')]
class Browse extends Component
{
    #[Url]
    public $categoryFilter = '';

    public function render()
    {
        $categories = BeachActivityCategory::active()
            ->withCount('services')
            ->get();

        $services = BeachService::where('is_active', true)
            ->with('category')
            ->when($this->categoryFilter, function ($query) {
                $query->where('beach_activity_category_id', $this->categoryFilter);
            })
            ->orderBy('name')
            ->get();

        return view('livewire.visitor.beach-activities.browse', [
            'categories' => $categories,
            'services' => $services,
        ]);
    }
}
