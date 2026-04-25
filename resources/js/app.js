import './bootstrap';
import 'bootstrap';

import Alpine from 'alpinejs';
import Choices from 'choices.js';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const labelsSelect = document.querySelector('#labels');

    if (labelsSelect) {
        new Choices(labelsSelect, {
            removeItemButton: true,
            shouldSort: false,
            searchEnabled: true,
            placeholder: true,
            placeholderValue: labelsSelect.dataset.placeholder || '',
            itemSelectText: '',
        });
    }
});
