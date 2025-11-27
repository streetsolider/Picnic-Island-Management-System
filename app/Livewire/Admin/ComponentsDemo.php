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

    // Toast notification states
    public $showToast = null;
    public $toastType = 'info';
    public $toastTitle = '';
    public $toastMessage = '';

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

    // Simple test method
    public function testToast()
    {
        $this->toastType = 'success';
        $this->toastTitle = 'Test';
        $this->toastMessage = 'This is a test';
        $this->showToast = uniqid();
    }

    // Show toast notification
    public function triggerToast($type)
    {
        $messages = [
            'info' => ['title' => 'Information', 'message' => 'This is an informational toast notification.'],
            'success' => ['title' => 'Success!', 'message' => 'Your action was completed successfully.'],
            'warning' => ['title' => 'Warning', 'message' => 'Please review this action carefully.'],
            'danger' => ['title' => 'Error', 'message' => 'An error occurred. Please try again.'],
        ];

        $this->toastType = $type;
        $this->toastTitle = $messages[$type]['title'];
        $this->toastMessage = $messages[$type]['message'];
        $this->showToast = uniqid(); // Unique ID to trigger re-render
    }

    public function render()
    {
        return view('livewire.admin.components-demo')
            ->layout('layouts.app');
    }
}
