import './bootstrap';
import 'bootstrap';

import Alpine from 'alpinejs';
import Choices from 'choices.js';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    // Метки в разделах: /tasks/{ID}/edit, /tasks/create
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

    // Фильтры на странице списка задач
    const elements = [
        '#filter_status_id',
        '#filter_created_by_id',
        '#filter_assigned_to_id',
        '#filter_label_id',
    ];

    elements.forEach(selector => {
        const el = document.querySelector(selector);

        if (el && !el.classList.contains('choices__input')) {
            new Choices(el, {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: false,
                allowHTML: false,
            });
        }
    });
});
