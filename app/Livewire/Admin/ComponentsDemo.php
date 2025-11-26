<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class ComponentsDemo extends Component
{
    // Button states for demo
    public bool $primaryLoading = false;
    public bool $secondaryLoading = false;
    public bool $successLoading = false;
    public bool $dangerLoading = false;
    public bool $warningLoading = false;

    // Sample action for buttons
    public function sampleAction($type)
    {
        $property = $type . 'Loading';
        $this->$property = true;

        // Simulate processing
        sleep(2);

        $this->$property = false;

        session()->flash('message', ucfirst($type) . ' button action completed!');
    }

    public function render()
    {
        return view('livewire.admin.components-demo')
            ->layout('layouts.app');
    }
}
