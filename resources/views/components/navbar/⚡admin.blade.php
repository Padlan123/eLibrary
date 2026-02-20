<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div>
    <header
        class="bg-linear-to-r from-blue-600 via-blue-400 to-blue-400 shadow-sm p-3 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar"
                aria-controls="default-sidebar" type="button"
                class="text-heading bg-transparent box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base ms-3 text-sm p-2 focus:outline-none inline-flex sm:hidden">
                <span class="sr-only">Open sidebar</span>
                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h10" />
                </svg>
            </button>
            {{-- <div class="overflow-hidden bg-white rounded-full">
                <img src="img/logo.jpeg" alt="logo" class="object-center aspect-square w-10" />
            </div> --}}
            <h2 class="text-white font-bold text-lg">Readify</h2>
        </div>
    </header> {{-- Be present above all else. - Naval Ravikant --}}
</div>
