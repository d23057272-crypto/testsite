<?php

namespace App\Livewire;

use Livewire\Component;

class Dropdowns extends Component
{
	public $isNotificationsMenuOpen = false;
    public $isProfileMenuOpen = false;

    public function toggleNotificationsMenu()
    {
        $this->isNotificationsMenuOpen = !$this->isNotificationsMenuOpen;
        if ($this->isNotificationsMenuOpen) {
            $this->isProfileMenuOpen = false; // Close Profile menu
        }
    }

    public function toggleProfileMenu()
    {
        $this->isProfileMenuOpen = !$this->isProfileMenuOpen;
        if ($this->isProfileMenuOpen) {
            $this->isNotificationsMenuOpen = false; // Close Notifications menu
        }
    }

    public function closeNotificationsMenu()
    {
        $this->isNotificationsMenuOpen = false;
    }

    public function closeProfileMenu()
    {
        $this->isProfileMenuOpen = false;
    }

    public function render()
    {
        return view('livewire.components.dropdowns');
    }
}
