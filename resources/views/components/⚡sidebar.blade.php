<?php

use Livewire\Component;

new class extends Component {
    public function logout()
    {
        Auth::logout();
        return redirect()->route('welcome');
    }
};
?>

<div>

    <aside id="default-sidebar"
        class="fixed top-0 left-0 z-50 w-52 h-full bg-linear-to-b from-gray-900 to-gray-800 text-white px-4 py-6
        transition-transform -translate-x-full duration-300 ease-in-out md:translate-x-0 md:relative md:w-50 md:min-h-screen lg:w-64">
        {{-- --}}
        <h1 class="text-lg font-bold mb-8">Admin Panel</h1>
        <nav class="space-y-4">

            <x-nav-link :route="'admin.dashboard'" :icon="'bar-chart-outline'" :label="'Dashboard'"></x-nav-link>

            <x-nav-link :route="'admin.ebook'" :icon="'book-outline'" :label="'Kelola E-Book'"></x-nav-link>

            <x-nav-link :route="'admin.pengguna'" :icon="'person-outline'" :label="'Kelola Pengguna'"></x-nav-link>

            <hr class="text-gray-600">

            <button data-modal-target="popup-modal" data-modal-toggle="popup-modal" data-drawer-hide="default-sidebar"
                type="button"
                class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-white font-bold shadow-lg hover:bg-red-500 transition duration-300 ease-in-out">
                <ion-icon class="size-4 pl-2" name="close"></ion-icon>
                logout
            </button>
        </nav>
    </aside>

    <div id="popup-modal" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-2/3 md:inset-0 h-[calc(100%-1rem)] max-h-full mx-auto lg:w-1/2">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-neutral-primary-soft border border-default rounded-base shadow-sm p-4 md:p-6">
                <button type="button"
                    class="absolute top-3 end-2.5 text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center"
                    data-modal-hide="popup-modal">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18 17.94 6M18 18 6.06 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-fg-disabled w-12 h-12" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-6 text-body">Apakah kamu yakin ingin Keluar? kamu harus login kembali</h3>
                    </h3>
                    <div class="flex items-center space-x-4 justify-center">
                        <button wire:click="logout" data-modal-hide="popup-modal" type="button"
                            class="text-white bg-danger box-border border border-transparent hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                            Keluar
                        </button>
                        <button data-modal-hide="popup-modal" type="button"
                            class="text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
