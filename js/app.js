// Global state
const state = {
    categories: [],
    equipment: [],
    templates: [],
    equipmentTypes: [],
    selectedEquipment: [],
    selectedCategories: new Set(),
    currentTemplate: null,
    currency: 'LKR '
};

// Get currency symbol
function getCurrency() {
    return 'LKR ';
}

// Initialize app
document.addEventListener('DOMContentLoaded', () => {
    initializeApp();
    attachEventListeners();
    // Calculate totals with default values
    calculateTotals();
});

// Initialize application
async function initializeApp() {
    try {
        await Promise.all([
            loadCategories(),
            loadEquipment(),
            loadTemplates(),
            loadEquipmentTypes()
        ]);
    } catch (error) {
        showToast('Error loading data: ' + error.message, 'error');
    }
}

// Load categories from API
async function loadCategories() {
    try {
        const response = await fetch('api/get_categories.php');
        const data = await response.json();
        
        if (data.success) {
            state.categories = data.data;
            renderCategoryCheckboxes();
        }
    } catch (error) {
        console.error('Error loading categories:', error);
        throw error;
    }
}

// Load equipment from API
async function loadEquipment() {
    try {
        const response = await fetch('api/get_equipment.php');
        const data = await response.json();
        
        if (data.success) {
            state.equipment = data.data;
        }
    } catch (error) {
        console.error('Error loading equipment:', error);
        throw error;
    }
}

// Load templates from API
async function loadTemplates() {
    try {
        const response = await fetch('api/get_templates.php');
        const data = await response.json();
        
        if (data.success) {
            state.templates = data.data;
            renderTemplateDropdown();
        }
    } catch (error) {
        console.error('Error loading templates:', error);
        throw error;
    }
}

// Load equipment types from API
async function loadEquipmentTypes() {
    try {
        const response = await fetch('api/get_equipment_types.php');
        const data = await response.json();
        
        if (data.success) {
            state.equipmentTypes = data.data;
            populateEquipmentTypeDropdown();
        }
    } catch (error) {
        console.error('Error loading equipment types:', error);
        throw error;
    }
}

// Render category checkboxes
function renderCategoryCheckboxes() {
    const container = document.getElementById('category-checkboxes');
    container.innerHTML = '';
    
    state.categories.forEach(category => {
        const div = document.createElement('div');
        div.className = 'category-checkbox';
        
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.id = `category-${category.id}`;
        checkbox.value = category.id;
        checkbox.addEventListener('change', (e) => handleCategoryChange(e, category));
        
        const label = document.createElement('label');
        label.htmlFor = `category-${category.id}`;
        label.textContent = category.name;
        
        div.appendChild(checkbox);
        div.appendChild(label);
        container.appendChild(div);
    });
    
    // Also populate the equipment category dropdown
    populateEquipmentCategoryDropdown();
}

// Populate type dropdown for new equipment
function populateEquipmentTypeDropdown() {
    const select = document.getElementById('new-equipment-type');
    select.innerHTML = '<option value="">-- Select Type --</option>';
    
    state.equipmentTypes.forEach(type => {
        const option = document.createElement('option');
        option.value = type.id;
        option.textContent = type.type;
        select.appendChild(option);
    });
}

// Render template dropdown
function renderTemplateDropdown() {
    const select = document.getElementById('template-select');
    
    // Clear existing options except first
    while (select.options.length > 1) {
        select.remove(1);
    }
    
    state.templates.forEach(template => {
        const option = document.createElement('option');
        option.value = template.id;
        option.textContent = template.name;
        select.appendChild(option);
    });
}

// Handle category checkbox change
function handleCategoryChange(event, category) {
    const isChecked = event.target.checked;
    
    if (isChecked) {
        state.selectedCategories.add(category.id);
        renderEquipmentDropdown(category);
    } else {
        state.selectedCategories.delete(category.id);
        removeEquipmentDropdown(category.id);
        // Remove equipment items from this category
        state.selectedEquipment = state.selectedEquipment.filter(
            item => item.category_id !== category.id
        );
        renderSelectedEquipment();
        calculateTotals();
    }
}

// Render equipment dropdown for a category
function renderEquipmentDropdown(category) {
    const container = document.getElementById('equipment-dropdowns');
    
    // Check if dropdown already exists
    if (document.getElementById(`dropdown-${category.id}`)) {
        return;
    }
    
    const groupDiv = document.createElement('div');
    groupDiv.className = 'equipment-dropdown-group';
    groupDiv.id = `dropdown-${category.id}`;
    
    const header = document.createElement('div');
    header.className = 'equipment-dropdown-header';
    
    const title = document.createElement('h3');
    title.className = 'equipment-dropdown-title';
    title.textContent = category.name;
    
    header.appendChild(title);
    groupDiv.appendChild(header);
    
    const controls = document.createElement('div');
    controls.className = 'equipment-dropdown-controls';
    
    const selectGroup = document.createElement('div');
    selectGroup.className = 'form-group';
    selectGroup.style.marginBottom = '0';
    
    const selectLabel = document.createElement('label');
    selectLabel.className = 'form-label';
    selectLabel.textContent = 'Select Equipment';
    
    const select = document.createElement('select');
    select.className = 'form-select';
    select.id = `equipment-select-${category.id}`;
    
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = '-- Select Equipment --';
    select.appendChild(defaultOption);
    
    // Get equipment for this category
    const categoryEquipment = state.equipment.filter(eq => eq.category_id === category.id);
    const currency = getCurrency();
    categoryEquipment.forEach(equipment => {
        const option = document.createElement('option');
        option.value = equipment.id;
        option.textContent = `${equipment.name} - ${currency}${parseFloat(equipment.value).toFixed(2)}`;
        option.dataset.equipment = JSON.stringify(equipment);
        select.appendChild(option);
    });
    
    selectGroup.appendChild(selectLabel);
    selectGroup.appendChild(select);
    
    const quantityGroup = document.createElement('div');
    quantityGroup.className = 'quantity-group';
    
    const quantityLabel = document.createElement('label');
    quantityLabel.className = 'form-label';
    quantityLabel.textContent = 'Qty';
    
    const quantityInput = document.createElement('input');
    quantityInput.type = 'number';
    quantityInput.className = 'form-input';
    quantityInput.id = `quantity-${category.id}`;
    quantityInput.min = '1';
    quantityInput.value = '1';
    
    quantityGroup.appendChild(quantityLabel);
    quantityGroup.appendChild(quantityInput);
    
    const addButton = document.createElement('button');
    addButton.className = 'btn btn-primary btn-small';
    addButton.textContent = 'Add';
    addButton.addEventListener('click', () => addEquipmentItem(category.id));
    
    controls.appendChild(selectGroup);
    controls.appendChild(quantityGroup);
    controls.appendChild(addButton);
    
    groupDiv.appendChild(controls);
    container.appendChild(groupDiv);
}

// Remove equipment dropdown
function removeEquipmentDropdown(categoryId) {
    const dropdown = document.getElementById(`dropdown-${categoryId}`);
    if (dropdown) {
        dropdown.remove();
    }
}

// Add equipment item to selection
function addEquipmentItem(categoryId) {
    const select = document.getElementById(`equipment-select-${categoryId}`);
    const quantityInput = document.getElementById(`quantity-${categoryId}`);
    
    if (!select.value) {
        showToast('Please select an equipment item', 'error');
        return;
    }
    
    const quantity = parseInt(quantityInput.value) || 1;
    const equipment = JSON.parse(select.options[select.selectedIndex].dataset.equipment);
    
    // Check if already added
    const existingIndex = state.selectedEquipment.findIndex(item => item.id === equipment.id);
    
    if (existingIndex >= 0) {
        // Update quantity
        state.selectedEquipment[existingIndex].quantity = quantity;
    } else {
        // Add new
        state.selectedEquipment.push({
            ...equipment,
            quantity: quantity
        });
    }
    
    // Reset select and quantity
    select.value = '';
    quantityInput.value = '1';
    
    renderSelectedEquipment();
    calculateTotals();
    showToast('Equipment added successfully', 'success');
}

// Remove equipment item
function removeEquipmentItem(equipmentId) {
    state.selectedEquipment = state.selectedEquipment.filter(item => item.id !== equipmentId);
    renderSelectedEquipment();
    calculateTotals();
    showToast('Equipment removed', 'success');
}

// Render selected equipment list
function renderSelectedEquipment() {
    const container = document.getElementById('selected-equipment-list');
    
    if (state.selectedEquipment.length === 0) {
        container.innerHTML = '<p class="empty-state">No equipment selected yet</p>';
        return;
    }
    
    container.innerHTML = '';
    const currency = getCurrency();
    
    state.selectedEquipment.forEach(item => {
        const div = document.createElement('div');
        div.className = 'equipment-item';
        
        const info = document.createElement('div');
        info.className = 'equipment-item-info';
        
        const name = document.createElement('div');
        name.className = 'equipment-item-name';
        name.textContent = item.name;
        
        const details = document.createElement('div');
        details.className = 'equipment-item-details';
        details.textContent = `${item.type} - ${item.model} | Qty: ${item.quantity} × ${currency}${parseFloat(item.value).toFixed(2)}`;
        
        info.appendChild(name);
        info.appendChild(details);
        
        const priceDiv = document.createElement('div');
        priceDiv.className = 'equipment-item-price';
        
        const value = document.createElement('span');
        value.className = 'equipment-item-value';
        const totalValue = parseFloat(item.value) * item.quantity;
        value.textContent = `${currency}${totalValue.toFixed(2)}`;
        
        const removeBtn = document.createElement('button');
        removeBtn.className = 'btn btn-danger btn-small';
        removeBtn.textContent = 'Remove';
        removeBtn.addEventListener('click', () => removeEquipmentItem(item.id));
        
        priceDiv.appendChild(value);
        priceDiv.appendChild(removeBtn);
        
        div.appendChild(info);
        div.appendChild(priceDiv);
        container.appendChild(div);
    });
}

// Calculate totals
function calculateTotals() {
    // Equipment total
    const equipmentTotal = state.selectedEquipment.reduce((sum, item) => {
        return sum + (parseFloat(item.value) * item.quantity);
    }, 0);
    
    // Labor total
    const laborHours = parseFloat(document.getElementById('labor-hours').value) || 0;
    const hourlyRate = parseFloat(document.getElementById('hourly-rate').value) || 0;
    const laborTotal = laborHours * hourlyRate;
    
    // Subtotal
    const subtotal = equipmentTotal + laborTotal;
    
    // Margin
    const marginPercentage = parseFloat(document.getElementById('margin-percentage').value) || 0;
    const marginAmount = subtotal * (marginPercentage / 100);
    
    // Final total
    const finalTotal = subtotal + marginAmount;
    
    // Update UI with currency
    const currency = getCurrency();
    document.getElementById('equipment-total').textContent = `${currency}${equipmentTotal.toFixed(2)}`;
    document.getElementById('labor-total').textContent = `${currency}${laborTotal.toFixed(2)}`;
    
    document.getElementById('breakdown-equipment').textContent = `${currency}${equipmentTotal.toFixed(2)}`;
    document.getElementById('breakdown-labor').textContent = `${currency}${laborTotal.toFixed(2)}`;
    document.getElementById('breakdown-subtotal').textContent = `${currency}${subtotal.toFixed(2)}`;
    document.getElementById('breakdown-margin').textContent = `${currency}${marginAmount.toFixed(2)}`;
    document.getElementById('breakdown-total').textContent = `${currency}${finalTotal.toFixed(2)}`;
    document.getElementById('margin-display').textContent = marginPercentage.toFixed(0);
}

// Handle template selection
function handleTemplateSelection() {
    const select = document.getElementById('template-select');
    const applyBtn = document.getElementById('apply-template-btn');
    const infoDiv = document.getElementById('template-info');
    
    if (select.value) {
        applyBtn.disabled = false;
        const template = state.templates.find(t => t.id == select.value);
        
        if (template) {
            state.currentTemplate = template;
            const currency = getCurrency();
            
            // Show template info
            document.getElementById('template-description').textContent = template.description || 'No description available';
            document.getElementById('template-hours').textContent = parseFloat(template.labor_hours).toFixed(1);
            document.getElementById('template-rate').textContent = `${currency}${parseFloat(template.hourly_rate).toFixed(2)}`;
            document.getElementById('template-margin').textContent = `${parseFloat(template.margin_percentage).toFixed(0)}%`;
            
            infoDiv.style.display = 'block';
        }
    } else {
        applyBtn.disabled = true;
        infoDiv.style.display = 'none';
        state.currentTemplate = null;
    }
}

// Apply template
function applyTemplate() {
    if (!state.currentTemplate) {
        showToast('Please select a template first', 'error');
        return;
    }
    
    // Save template reference before reset
    const template = state.currentTemplate;
    
    // Reset current selections
    resetAll(false);
    
    // Set labor values
    document.getElementById('labor-hours').value = parseFloat(template.labor_hours).toFixed(1);
    document.getElementById('hourly-rate').value = parseFloat(template.hourly_rate).toFixed(2);
    document.getElementById('margin-percentage').value = parseFloat(template.margin_percentage).toFixed(0);
    
    // Get unique categories from template equipment
    const templateCategories = new Set(template.equipment.map(eq => eq.category_id));
    
    // Check category checkboxes and render dropdowns
    templateCategories.forEach(categoryId => {
        const checkbox = document.getElementById(`category-${categoryId}`);
        if (checkbox) {
            checkbox.checked = true;
            state.selectedCategories.add(categoryId);
            const category = state.categories.find(c => c.id == categoryId);
            if (category) {
                renderEquipmentDropdown(category);
            }
        }
    });
    
    // Wait for dropdowns to be fully rendered before adding equipment
    setTimeout(() => {
        // Add equipment items from template
        template.equipment.forEach(templateEquip => {
            // Find equipment by ID (use loose equality for type coercion)
            const equipment = state.equipment.find(eq => eq.id == templateEquip.equipment_id);
            if (equipment) {
                state.selectedEquipment.push({
                    ...equipment,
                    quantity: parseInt(templateEquip.quantity) || 1
                });
            }
        });
        
        renderSelectedEquipment();
        calculateTotals();
        showToast('Template applied successfully', 'success');
    }, 150);
}

// Download PDF
function downloadPDF() {
    const packageName = document.getElementById('package-name').value.trim();
    
    if (!packageName) {
        showToast('Please enter a package name first', 'error');
        return;
    }
    
    if (state.selectedEquipment.length === 0) {
        showToast('Please add at least one equipment item', 'error');
        return;
    }
    
    // Calculate values
    const equipmentTotal = state.selectedEquipment.reduce((sum, item) => {
        return sum + (parseFloat(item.value) * item.quantity);
    }, 0);
    
    const laborHours = parseFloat(document.getElementById('labor-hours').value) || 0;
    const hourlyRate = parseFloat(document.getElementById('hourly-rate').value) || 0;
    const laborTotal = laborHours * hourlyRate;
    
    const subtotal = equipmentTotal + laborTotal;
    const marginPercentage = parseFloat(document.getElementById('margin-percentage').value) || 0;
    const marginAmount = subtotal * (marginPercentage / 100);
    const finalTotal = subtotal + marginAmount;
    
    // Build query string
    const params = new URLSearchParams({
        package_name: packageName,
        client_name: document.getElementById('client-name').value.trim(),
        labor_hours: laborHours,
        hourly_rate: hourlyRate,
        margin_percentage: marginPercentage,
        equipment_total: equipmentTotal.toFixed(2),
        labor_total: laborTotal.toFixed(2),
        subtotal: subtotal.toFixed(2),
        margin_amount: marginAmount.toFixed(2),
        final_total: finalTotal.toFixed(2),
        notes: document.getElementById('package-notes').value.trim(),
        equipment: JSON.stringify(state.selectedEquipment.map(item => ({
            name: item.name,
            type: item.type,
            model: item.model,
            quantity: item.quantity,
            unit_value: parseFloat(item.value).toFixed(2),
            total_value: (parseFloat(item.value) * item.quantity).toFixed(2)
        }))),
        currency: getCurrency()
    });
    
    // Open PDF in new window
    const url = `api/generate_pdf.php?${params.toString()}`;
    window.open(url, '_blank');
    showToast('Opening PDF in new window...', 'success');
}

// Download Package Details PDF
function downloadPackagePDF() {
    const packageName = document.getElementById('package-name').value.trim();
    const clientName = document.getElementById('client-name').value.trim();
    const notes = document.getElementById('package-notes').value.trim();
    
    if (!packageName) {
        showToast('Please enter a package name first', 'error');
        return;
    }
    
    // Calculate final total
    const equipmentTotal = state.selectedEquipment.reduce((sum, item) => {
        return sum + (parseFloat(item.value) * item.quantity);
    }, 0);
    
    const laborHours = parseFloat(document.getElementById('labor-hours').value) || 0;
    const hourlyRate = parseFloat(document.getElementById('hourly-rate').value) || 0;
    const laborTotal = laborHours * hourlyRate;
    
    const subtotal = equipmentTotal + laborTotal;
    const marginPercentage = parseFloat(document.getElementById('margin-percentage').value) || 0;
    const marginAmount = subtotal * (marginPercentage / 100);
    const finalTotal = subtotal + marginAmount;
    
    // Build query string
    const params = new URLSearchParams({
        package_name: packageName,
        client_name: clientName,
        notes: notes,
        final_total: finalTotal.toFixed(2),
        event_hours: laborHours.toFixed(1),
        currency: getCurrency()
    });
    
    // Open PDF in new window
    const url = `api/generate_package_pdf.php?${params.toString()}`;
    window.open(url, '_blank');
    showToast('Opening package details PDF...', 'success');
}

// Reset all
function resetAll(showMessage = true) {
    // Uncheck all categories
    state.selectedCategories.clear();
    state.categories.forEach(category => {
        const checkbox = document.getElementById(`category-${category.id}`);
        if (checkbox) {
            checkbox.checked = false;
        }
    });
    
    // Clear equipment dropdowns
    document.getElementById('equipment-dropdowns').innerHTML = '';
    
    // Clear selected equipment
    state.selectedEquipment = [];
    renderSelectedEquipment();
    
    // Reset template
    document.getElementById('template-select').value = '';
    document.getElementById('template-info').style.display = 'none';
    document.getElementById('apply-template-btn').disabled = true;
    state.currentTemplate = null;
    
    // Reset labor inputs to defaults
    document.getElementById('labor-hours').value = '2';
    document.getElementById('hourly-rate').value = '1000';
    document.getElementById('margin-percentage').value = '10';
    
    // Reset package inputs
    document.getElementById('package-name').value = '';
    document.getElementById('client-name').value = '';
    document.getElementById('package-notes').value = '';
    
    // Recalculate
    calculateTotals();
    
    if (showMessage) {
        showToast('All fields have been reset', 'success');
    }
}

// Add new equipment
async function addNewEquipment() {
    // Get form values
    const categoryId = document.getElementById('new-equipment-category').value;
    const typeId = document.getElementById('new-equipment-type').value;
    const model = document.getElementById('new-equipment-model').value.trim();
    const name = document.getElementById('new-equipment-name').value.trim();
    const value = document.getElementById('new-equipment-value').value;
    const description = document.getElementById('new-equipment-description').value.trim();
    
    // Validate required fields
    if (!categoryId || !typeId || !model || !name || !value) {
        showToast('Please fill in all required fields', 'error');
        return;
    }
    
    if (parseFloat(value) < 0) {
        showToast('Value must be a positive number', 'error');
        return;
    }
    
    // Prepare data
    const equipmentData = {
        category_id: parseInt(categoryId),
        type: parseInt(typeId),
        model: model,
        name: name,
        value: parseFloat(value),
        description: description
    };
    
    try {
        const response = await fetch('api/add_equipment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(equipmentData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast(result.message, 'success');
            clearEquipmentForm();
            closeAddEquipmentModal();
        } else {
            showToast(result.message, 'error');
        }
    } catch (error) {
        console.error('Error adding equipment:', error);
        showToast('Failed to add equipment. Please try again.', 'error');
    }
}

// Clear equipment form
function clearEquipmentForm() {
    document.getElementById('new-equipment-category').value = '';
    document.getElementById('new-equipment-type').value = '';
    document.getElementById('new-equipment-model').value = '';
    document.getElementById('new-equipment-name').value = '';
    document.getElementById('new-equipment-value').value = '';
    document.getElementById('new-equipment-description').value = '';
}

// Populate category dropdown for new equipment
function populateEquipmentCategoryDropdown() {
    const select = document.getElementById('new-equipment-category');
    select.innerHTML = '<option value="">-- Select Category --</option>';
    
    state.categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;
        select.appendChild(option);
    });
}

// Show toast notification
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const messageEl = document.getElementById('toast-message');
    
    messageEl.textContent = message;
    toast.className = `toast ${type}`;
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

// Attach event listeners
function attachEventListeners() {
    // Template
    document.getElementById('template-select').addEventListener('change', handleTemplateSelection);
    document.getElementById('apply-template-btn').addEventListener('click', applyTemplate);
    
    // Modal controls
    document.getElementById('open-add-equipment-btn').addEventListener('click', openAddEquipmentModal);
    document.getElementById('close-modal-btn').addEventListener('click', closeAddEquipmentModal);
    document.getElementById('add-equipment-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddEquipmentModal();
        }
    });
    
    // Add Equipment
    document.getElementById('add-equipment-btn').addEventListener('click', addNewEquipment);
    document.getElementById('clear-equipment-form-btn').addEventListener('click', clearEquipmentForm);
    
    // Labor inputs
    document.getElementById('labor-hours').addEventListener('input', calculateTotals);
    document.getElementById('hourly-rate').addEventListener('input', calculateTotals);
    document.getElementById('margin-percentage').addEventListener('input', calculateTotals);
    
    // Actions
    document.getElementById('download-pdf-btn').addEventListener('click', downloadPDF);
    document.getElementById('download-package-pdf-btn').addEventListener('click', downloadPackagePDF);
    document.getElementById('reset-btn').addEventListener('click', () => resetAll(true));
}

// Open Add Equipment Modal
function openAddEquipmentModal() {
    const modal = document.getElementById('add-equipment-modal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

// Close Add Equipment Modal
function closeAddEquipmentModal() {
    const modal = document.getElementById('add-equipment-modal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
}

