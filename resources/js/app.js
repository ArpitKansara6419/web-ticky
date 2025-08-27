import './bootstrap';
import 'flowbite';
import { Dropdown } from 'flowbite';
import Alpine from 'alpinejs';
import 'flowbite/dist/flowbite.min.css';
import $ from 'jquery';
// window.$ = $;
// window.jQuery = $;

import select2 from 'select2';
import 'select2/dist/css/select2.min.css';
window.select2 = select2;
import { Tooltip } from 'flowbite';
import { Modal } from 'flowbite';

window.Alpine = Alpine;

Alpine.start();


window.Modal = Modal;

document.addEventListener('DOMContentLoaded', function() {
    initFlowbiteModals();
});

// Get all tooltip triggers with a common attribute
window.initializeTooltips = async function () {
    document.querySelectorAll('[data-tooltip-target]').forEach(($triggerEl) => {
        const tooltipId = $triggerEl.getAttribute('data-tooltip-target');
        const $targetEl = document.getElementById(tooltipId);

        if ($targetEl) {
            const options = {
                placement: 'bottom',
                triggerType: 'hover',
                // onHide: () => console.log('tooltip is hidden'),
                // onShow: () => console.log('tooltip is shown'),
                // onToggle: () => console.log('tooltip is toggled'),
            };

            const instanceOptions = {
                id: tooltipId,
                override: true,
            };

            new Tooltip($targetEl, $triggerEl, options, instanceOptions);
        }
    });
}

window.initializeDropdowns = function () {
    /*var $targetEl = document.getElementById('dropdownLeftEnd_57');

    console.log("DROPDOWN DIV =>",  $targetEl)

    // console.log("=>", $targetEl)

    // set the element that trigger the dropdown menu on click
    var $triggerEl = document.getElementById('dropdownLeftEndButton_57');

    console.log("triggerEl button =>",  $triggerEl)

    // options with default values
    var options = {
        placement: 'bottom',
        triggerType: 'click',
        offsetSkidding: 0,
        offsetDistance: 10,
        delay: 300,
        ignoreClickOutsideClass: false,
        onHide: () => {
            console.log('dropdown has been hidden');
        },
        onShow: () => {
            console.log('dropdown has been shown');
        },
        onToggle: () => {
            console.log('dropdown has been toggled');
        },
    };

    // instance options object
    var instanceOptions = {
        id: 'dropdownLeftEnd_57',
        override: true
    };

    var dropdown = new Dropdown($targetEl, $triggerEl, {
        placement: 'bottom',
        triggerType: 'click',
        offsetSkidding: 0,
        offsetDistance: 10,
        delay: 300,
        onHide: () => console.log('dropdown hidden'),
        onShow: () => console.log('dropdown shown'),
        onToggle: () => console.log('dropdown toggled'),
    }, instanceOptions);*/

    // dropdown.show();

    document.querySelectorAll('.dropdown-trigger').forEach(($triggerEl) => {
        var dropDownMenuId = $triggerEl.getAttribute('data-dropdown-toggle');
        var buttonId = $triggerEl.getAttribute('id');
        
        var $targetEl = document.getElementById(dropDownMenuId);

        var $triggerEl = document.getElementById(buttonId);

        

        
        var instanceOptions = {
            placement: 'top',
            triggerType: 'click',
            offsetSkidding: 0,
            offsetDistance: 10,
            delay: 300,
            ignoreClickOutsideClass: false,
            onHide: () => {
                // console.log('dropdown has been hidden');
            },
            onShow: () => {
                // console.log('dropdown has been shown');
            },
            onToggle: () => {
                // console.log('dropdown has been toggled');
            },
        };

        // instance options object
        var instanceOptions = {
            id: dropDownMenuId,
            override: true
        };

        var dropdown = new Dropdown($targetEl, $triggerEl, {
            placement: 'bottom-start',
            triggerType: 'click',
            offsetSkidding: 0,
            offsetDistance: 10,
            delay: 300,
            onHide: () => console.log('dropdown hidden'),
            onShow: () => console.log('dropdown shown'),
            onToggle: () => console.log('dropdown toggled'),
        }, instanceOptions);
    });


    
    
    /*document.querySelectorAll('.dropdown-trigger').forEach(($triggerEl) => {
        const dropdownId = $triggerEl.getAttribute('id');
        if (!dropdownId) return;

        const $targetEl = document.getElementById(dropdownId);
        if (!$targetEl) return;

        const instanceOptions = {
            id: dropdownId,
            override: true,
        };

        new Dropdown($targetEl, $triggerEl, {
            placement: 'bottom',
            triggerType: 'click',
            offsetSkidding: 0,
            offsetDistance: 10,
            delay: 300,
            onHide: () => console.log('dropdown hidden'),
            onShow: () => console.log('dropdown shown'),
            onToggle: () => console.log('dropdown toggled'),
        }, instanceOptions);
    });*/
}

window.initFlowbiteModals = function () {
    /*if (typeof Modal === 'undefined') {
        console.error('Flowbite Modal class is not available');
        return;
    }

    document.querySelectorAll('[data-modal-target]').forEach((el) => {
        const target = el.getAttribute('data-modal-target');
        const modalEl = document.getElementById(target.replace('#', '')); // Remove '#' if present
        
        if (modalEl) {
            const modalInstance = Modal.getOrCreateInstance(modalEl); // Use getOrCreateInstance instead of new Modal()
            console.log('Modal initialized:', modalEl.id);
        } else {
            console.error('Modal element not found:', target);
        }
    });*/
};


