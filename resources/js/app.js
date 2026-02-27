import './bootstrap';
import { initFlowbite } from 'flowbite';

document.addEventListener('livewire:navigated', () => {
    initFlowbite();
});