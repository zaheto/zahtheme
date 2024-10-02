console.log('Calculator script loaded');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded and parsed');

    const form = document.getElementById('blinds-calculator');
    const addRowButtons = document.querySelectorAll('.add-row');
    const calculateButton = document.querySelector('button[type="submit"]');
    const tabs = document.querySelectorAll('.tabs a');
    const tabContents = document.querySelectorAll('.tab-content');

    console.log('Form:', form);
    console.log('Add Row Buttons:', addRowButtons);
    console.log('Calculate Button:', calculateButton);
    console.log('Tabs:', tabs);
    console.log('Tab Contents:', tabContents);

    // Tab functionality
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Tab clicked:', this.getAttribute('href'));
            
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(tc => tc.classList.remove('active'));
            
            this.classList.add('active');
            document.querySelector(this.getAttribute('href')).classList.add('active');
        });
    });

    // Add new row
    addRowButtons.forEach(button => {
        button.addEventListener('click', function() {
            console.log('Add row button clicked');
            const model = this.dataset.model;
            const container = document.getElementById(model);
            const newRow = container.querySelector('.row').cloneNode(true);
            const inputs = newRow.querySelectorAll('input, select');
            const rowIndex = container.querySelectorAll('.row').length;

            console.log('New row created for model:', model);
            console.log('Row index:', rowIndex);

            inputs.forEach(input => {
                input.name = input.name.replace(/\[\d+\]/, `[${rowIndex}]`);
                input.value = '';
                console.log('Updated input name:', input.name);
            });

            container.insertBefore(newRow, this);
        });
    });

    // Real-time calculations
    form.addEventListener('input', debounce(calculateMaterials, 300));

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Form submitted');
        calculateMaterials();
    });

    function calculateMaterials() {
        console.log('Calculating materials');
        const formData = new FormData(form);

        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-WP-Nonce': document.querySelector('#_wpnonce').value
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Calculation result:', data);
            if (data.error) {
                displayError(data.message);
            } else {
                displayResults(data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            displayError('An unexpected error occurred. Please try again.');
        });
    }

    function displayResults(data) {
        console.log('Displaying results');
        const resultsContainer = document.getElementById('results');
        resultsContainer.innerHTML = '';
    
        for (const [model, materials] of Object.entries(data)) {
            console.log('Displaying results for model:', model);
            const modelResults = document.createElement('div');
            modelResults.innerHTML = `<h3>${model.toUpperCase()} Materials</h3>`;
    
            materials.forEach((row, index) => {
                console.log('Row:', index + 1, row);
                const rowElement = document.createElement('div');
                rowElement.innerHTML = `
                    <h4>Row ${index + 1}</h4>
                    <ul>
                        <li>Blinds Profile: ${row.blinds_profile} Pcs. ${row.blinds_profile_lm} lm</li>
                        <li>U Profile Left: ${row.u_profile_left} Pcs. ${row.u_profile_left_lm} lm</li>
                        <li>U Profile Right: ${row.u_profile_right} Pcs. ${row.u_profile_right_lm} lm</li>
                        <li>Horizontal U Profile: ${row.horizontal_u_profile} Pcs. ${row.horizontal_u_profile_lm} lm</li>
                        <li>Reinforcing Profile: ${row.reinforcing_profile} Pcs. ${row.reinforcing_profile_lm} lm</li>
                        <li>Rivets: ${row.rivets} Pcs.</li>
                        <li>Self-tapping Screws: ${row.self_tapping_screws} Pcs.</li>
                        <li>Dowels: ${row.dowels} Pcs.</li>
                        <li>Corner: ${row.corner} Pcs.</li>
                    </ul>
                `;
                modelResults.appendChild(rowElement);
            });
    
            resultsContainer.appendChild(modelResults);
        }
    }

    function displayError(message) {
        console.error('Error:', message);
        const resultsContainer = document.getElementById('results');
        resultsContainer.innerHTML = `<div class="error-message bg-red border border-red text-white px-4 py-3 rounded relative" role="alert">${message}</div>`;
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Activate the first tab by default
    if (tabs.length > 0) {
        tabs[0].click();
    }
});