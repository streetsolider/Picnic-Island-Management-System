<?php

namespace App\Livewire\Admin\Ferry;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public $activeTab = 'vessels';

    protected $queryString = ['activeTab' => ['except' => 'vessels']];

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.ferry.index');
    }
}
