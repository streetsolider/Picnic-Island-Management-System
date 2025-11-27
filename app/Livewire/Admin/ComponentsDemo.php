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

    // Modal demo properties
    public $formName = '';
    public $formEmail = '';

    // Sample action for form modal
    public function saveForm()
    {
        sleep(1); // Simulate processing

        $this->triggerToast('success');
        $this->toastTitle = 'Form Saved!';
        $this->toastMessage = "Name: {$this->formName}, Email: {$this->formEmail}";

        // Reset form
        $this->formName = '';
        $this->formEmail = '';
    }

    // Sample action for confirmation modal
    public function deleteItem()
    {
        sleep(1); // Simulate processing

        $this->triggerToast('success');
        $this->toastTitle = 'Item Deleted';
        $this->toastMessage = 'The item has been successfully deleted.';
    }

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
