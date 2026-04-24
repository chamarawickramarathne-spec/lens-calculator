/**
 * Lens Calculator - Assistant Logic
 * Author: Antigravity
 */

const state = {
    categories: [],
    equipment: [],
    templates: [],
    equipmentTypes: [],
    selectedEquipment: [],
    selectedCategories: new Set(),
    currentTemplate: null,
    currency: 'LKR ',
    currentStep: 1
};

document.addEventListener('DOMContentLoaded', () => {
    logAccess();
    initializeApp();
    attachEventListeners();
    goToStep(1);
    calculateTotals();
});

// Log application access
async function logAccess() {
    try {
        const response = await fetch('api/log_access.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json();
        if (data.success) {
            console.log('Access logged:', data.message);
        }
    } catch (e) {
        console.error('Failed to log access:', e);
    }
}

async function initializeApp() {
    try {
        const [cats, equip, temps, types] = await Promise.all([
            fetch('api/get_categories.php').then(r => r.json()),
            fetch('api/get_equipment.php').then(r => r.json()),
            fetch('api/get_templates.php').then(r => r.json()),
            fetch('api/get_equipment_types.php').then(r => r.json())
        ]);

        if (cats.success) {
            state.categories = cats.data;
            renderCategoryCheckboxes();
            populateModalCategories();
        }
        if (equip.success) state.equipment = equip.data;
        if (temps.success) renderTemplateDropdown(temps.data);
        if (types.success) {
            state.equipmentTypes = types.data;
            populateModalTypes();
        }

    } catch (e) {
        showToast('System initialization failed.', 'error');
    }
}

function renderCategoryCheckboxes() {
    const container = document.getElementById('category-checkboxes');
    if (!container) return;
    container.innerHTML = '';
    state.categories.forEach(cat => {
        const div = document.createElement('label');
        div.className = 'category-checkbox';
        div.innerHTML = `
            <input type="checkbox" value="${cat.id}" id="cat-${cat.id}">
            <span>${cat.name}</span>
        `;
        const cb = div.querySelector('input');
        cb.addEventListener('change', () => handleCategoryToggle(cat, cb.checked));
        container.appendChild(div);
    });
}

function handleCategoryToggle(cat, isChecked) {
    if (isChecked) {
        state.selectedCategories.add(cat.id);
        renderEquipmentDropdown(cat);
    } else {
        state.selectedCategories.delete(cat.id);
        document.getElementById(`dropdown-group-${cat.id}`)?.remove();
        state.selectedEquipment = state.selectedEquipment.filter(e => e.category_id != cat.id);
        renderSelectedItems();
        calculateTotals();
    }
}

function renderEquipmentDropdown(cat) {
    const mainContainer = document.getElementById('equipment-dropdowns');
    if (!mainContainer) return;
    const div = document.createElement('div');
    div.className = 'form-group dropdown-group';
    div.id = `dropdown-group-${cat.id}`;
    div.innerHTML = `
        <label class="form-label">${cat.name}</label>
        <select class="form-select equipment-select" id="select-${cat.id}">
            <option value="">-- Choose ${cat.name} --</option>
        </select>
    `;
    mainContainer.appendChild(div);

    const select = div.querySelector('select');
    const items = state.equipment.filter(e => e.category_id == cat.id);
    items.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.id;
        opt.textContent = `${item.name} (${state.currency}${parseFloat(item.value).toFixed(0)})`;
        opt.dataset.item = JSON.stringify(item);
        select.appendChild(opt);
    });

    $(select).select2({ width: '100%' });
    $(select).on('select2:select', (e) => {
        const item = JSON.parse(e.params.data.element.dataset.item);
        addGear(item);
        $(select).val('').trigger('change');
    });
}

function addGear(item) {
    const existing = state.selectedEquipment.find(e => e.id == item.id);
    if (existing) {
        existing.quantity++;
    } else {
        state.selectedEquipment.push({ ...item, quantity: 1 });
    }
    renderSelectedItems();
    calculateTotals();
    showToast(`${item.name} added.`);
}

function renderSelectedItems() {
    const list = document.getElementById('selected-equipment-list');
    if (!list) return;
    list.innerHTML = '';
    if (state.selectedEquipment.length === 0) {
        list.innerHTML = '<p class="empty-state">No gear selected</p>';
        return;
    }

    state.selectedEquipment.forEach(item => {
        const div = document.createElement('div');
        div.className = 'equipment-item-row';
        div.style.cssText = 'display:flex; justify-content:space-between; align-items:center; padding:0.75rem; background:#f9fafb; margin-bottom:0.5rem; border-radius:8px; font-size:0.875rem;';
        div.innerHTML = `
            <div>
                <strong>${item.name}</strong><br>
                <small>${item.quantity} x ${state.currency}${parseFloat(item.value).toLocaleString()}</small>
            </div>
            <button class="btn-remove" style="color:#ef4444; border:1px solid #fee2e2; background:white; padding:4px 8px; border-radius:4px; cursor:pointer;" onclick="removeGear(${item.id})">Remove</button>
        `;
        list.appendChild(div);
    });
}

window.removeGear = (id) => {
    state.selectedEquipment = state.selectedEquipment.filter(e => e.id != id);
    renderSelectedItems();
    calculateTotals();
};

function calculateTotals() {
    const gearTotal = state.selectedEquipment.reduce((sum, item) => sum + (parseFloat(item.value) * item.quantity), 0);
    const hours = parseFloat(document.getElementById('labor-hours').value) || 0;
    const rate = parseFloat(document.getElementById('hourly-rate').value) || 0;
    const laborTotal = hours * rate;
    const extraGear = parseFloat(document.getElementById('extra-gear-cost').value) || 0;
    const transportation = parseFloat(document.getElementById('transportation-cost').value) || 0;
    const assistantPay = parseFloat(document.getElementById('assistant-pay').value) || 0;
    const editingCost = parseFloat(document.getElementById('editing-cost').value) || 0;
    const additionalCost = parseFloat(document.getElementById('additional-cost').value) || 0;
    const margin = parseFloat(document.getElementById('margin-percentage').value) || 0;

    const additionalTotal = extraGear + transportation + assistantPay + editingCost + additionalCost;
    const subtotal = gearTotal + laborTotal + additionalTotal;
    const profit = subtotal * (margin / 100);
    const finalTotal = subtotal + profit;

    const gearEl = document.getElementById('equipment-total');
    if (gearEl) gearEl.textContent = `${state.currency}${gearTotal.toLocaleString()}`;
    
    document.getElementById('sticky-total').textContent = `${state.currency}${finalTotal.toLocaleString()}`;

    renderBreakdown(gearTotal, laborTotal, extraGear, transportation, assistantPay, editingCost, additionalCost, margin, profit, finalTotal);
}

function renderBreakdown(gear, labor, extraGear, transportation, assistantPay, editingCost, additionalCost, marginPct, profit, total) {
    const container = document.getElementById('final-breakdown-container');
    if (!container) return;

    const additionalTotal = extraGear + transportation + assistantPay + editingCost + additionalCost;

    const row = (label, value, style = '') =>
        value > 0 || label.includes('Cost') || label.includes('Service') ? `
        <div style="display:flex; justify-content:space-between; margin-bottom:0.65rem; ${style}">
            <span style="color:#555">${label}</span>
            <span style="font-weight:600">${state.currency}${value.toLocaleString()}</span>
        </div>` : '';

    container.innerHTML = `
        <div style="padding:1.5rem; background:#f9fafb; border-radius:16px; border:1px solid #eee;">
            ${row('Gear Costs:', gear)}
            ${row('Service Cost:', labor)}
            ${additionalTotal > 0 ? `
            <div style="border-top:1px dashed #ddd; margin:0.75rem 0; padding-top:0.75rem;">
                <span style="font-size:0.75rem; color:#999; text-transform:uppercase; letter-spacing:0.05em;">Additional Costs</span>
            </div>
            ${extraGear > 0 ? row('Extra Gear Cost:', extraGear) : ''}
            ${transportation > 0 ? row('Transportation Cost:', transportation) : ''}
            ${assistantPay > 0 ? row('Assistant Pay:', assistantPay) : ''}
            ${editingCost > 0 ? row('Editing / Retouching:', editingCost) : ''}
            ${additionalCost > 0 ? row('Additional Cost:', additionalCost) : ''}
            ` : ''}
            <div style="display:flex; justify-content:space-between; margin-bottom:0.65rem; margin-top:0.75rem; border-top:1px dashed #ddd; padding-top:0.75rem;">
                <span style="color:#555">Subtotal:</span>
                <span style="font-weight:600">${state.currency}${(gear + labor + additionalTotal).toLocaleString()}</span>
            </div>
            <div style="display:flex; justify-content:space-between; margin-bottom:0.75rem; color:#10b981; font-weight:600;">
                <span>Business Margin (${marginPct}%):</span>
                <span>${state.currency}${profit.toLocaleString()}</span>
            </div>
            <div style="display:flex; justify-content:space-between; margin-top:1rem; padding-top:1rem; border-top:2px solid #ddd; font-weight:800; font-size:1.25rem;">
                <span>Grand Total:</span>
                <span style="color:var(--accent-orange)">${state.currency}${total.toLocaleString()}</span>
            </div>
        </div>
    `;
}

function goToStep(step) {
    state.currentStep = step;
    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
    const targetSection = document.getElementById(`step-${step}`);
    if (targetSection) targetSection.classList.add('active');

    document.querySelectorAll('.step-indicator').forEach(ind => {
        const s = parseInt(ind.dataset.step);
        ind.classList.remove('active', 'completed');
        if (s == step) ind.classList.add('active');
        if (s < step) ind.classList.add('completed');
    });

    const mobileBtn = document.getElementById('mobile-next-btn');
    if (mobileBtn) mobileBtn.textContent = step === 4 ? 'Download PDF' : 'Next Step';

    // Always recalculate when entering the final step
    if (step === 4) calculateTotals();
    
    window.scrollTo({ top: 30, behavior: 'smooth' });
}

function attachEventListeners() {
    // Navigation
    document.querySelectorAll('.next-step, .prev-step, .btn-next-tab').forEach(b => {
        b.addEventListener('click', () => {
            const next = b.dataset.next || b.dataset.prev;
            if (next) goToStep(parseInt(next));
        });
    });
    
    document.getElementById('mobile-next-btn')?.addEventListener('click', () => {
        if (state.currentStep < 4) goToStep(state.currentStep + 1);
        else downloadPDF();
    });

    // Step indicators click
    document.querySelectorAll('.step-indicator').forEach(ind => {
        ind.addEventListener('click', () => goToStep(parseInt(ind.dataset.step)));
    });

    // Inputs
    ['labor-hours', 'hourly-rate', 'extra-gear-cost', 'transportation-cost', 'assistant-pay', 'editing-cost', 'additional-cost', 'margin-percentage'].forEach(id => {
        document.getElementById(id)?.addEventListener('input', calculateTotals);
    });

    // Modals
    document.getElementById('open-user-guide-btn')?.addEventListener('click', () => document.getElementById('user-guide-modal').classList.add('show'));
    document.getElementById('close-guide-modal-btn')?.addEventListener('click', closeUserGuideModal);

    // Accessibility: close modal with Escape key
    document.addEventListener('keydown', function (e) {
        const modal = document.getElementById('user-guide-modal');
        if (modal.classList.contains('show') && (e.key === 'Escape' || e.key === 'Esc')) {
            closeUserGuideModal();
        }
    });

    // Accessibility: close modal when clicking outside content
    document.getElementById('user-guide-modal')?.addEventListener('mousedown', function(e) {
        if (e.target === this) closeUserGuideModal();
    });

    function closeUserGuideModal() {
        document.getElementById('user-guide-modal').classList.remove('show');
        document.getElementById('close-guide-modal-btn').blur();
    }
    document.getElementById('open-add-new-btn')?.addEventListener('click', () => document.getElementById('add-equipment-modal').classList.add('show'));
    document.getElementById('close-modal-btn')?.addEventListener('click', () => document.getElementById('add-equipment-modal').classList.remove('show'));

    // Template
    document.getElementById('template-select')?.addEventListener('change', (e) => {
        const btn = document.getElementById('apply-template-btn');
        if (btn) btn.disabled = !e.target.value;
    });
    document.getElementById('apply-template-btn')?.addEventListener('click', applyTemplate);

    // Actions
    document.getElementById('download-pdf-btn')?.addEventListener('click', downloadPDF);
    document.getElementById('download-package-pdf-btn')?.addEventListener('click', downloadPackagePDF);
    document.getElementById('reset-btn')?.addEventListener('click', () => window.location.reload());
}

async function applyTemplate() {

    const id = document.getElementById('template-select').value;
    const res = await fetch(`api/get_templates.php`).then(r => r.json());
    if (!res.success) return;
    const temp = res.data.find(t => t.id == id);
    if (!temp) return;

    state.selectedEquipment = [];
    temp.equipment.forEach(te => {
        const item = state.equipment.find(e => e.id == te.equipment_id);
        if (item) state.selectedEquipment.push({ ...item, quantity: parseInt(te.quantity) });
    });

    document.getElementById('labor-hours').value = temp.labor_hours;
    document.getElementById('hourly-rate').value = temp.hourly_rate;
    document.getElementById('margin-percentage').value = temp.margin_percentage;

    renderSelectedItems();
    calculateTotals();
    showToast('Template factors applied.');
    goToStep(2);
}

function renderTemplateDropdown(data) {
    state.templates = data;
    const sel = document.getElementById('template-select');
    if (!sel) return;
    data.forEach(t => {
        const opt = document.createElement('option');
        opt.value = t.id;
        opt.textContent = t.name;
        sel.appendChild(opt);
    });
}

function populateModalCategories() {
    const sel = document.getElementById('new-equipment-category');
    if (!sel) return;
    sel.innerHTML = '<option value="">-- Choose Category --</option>';
    state.categories.forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.id;
        opt.textContent = c.name;
        sel.appendChild(opt);
    });
}

function populateModalTypes() {
    const sel = document.getElementById('new-equipment-type');
    if (!sel) return;
    sel.innerHTML = '<option value="">-- Choose Type --</option>';
    state.equipmentTypes.forEach(t => {
        const opt = document.createElement('option');
        opt.value = t.id;
        opt.textContent = t.type;
        sel.appendChild(opt);
    });
}

function showToast(msg, type = 'success') {
    const t = document.getElementById('toast');
    if (!t) return;
    document.getElementById('toast-message').textContent = msg;
    t.className = `toast ${type} show`;
    setTimeout(() => t.classList.remove('show'), 3000);
}

function downloadPDF() {
    const gearTotal = state.selectedEquipment.reduce((sum, item) => sum + (parseFloat(item.value) * item.quantity), 0);
    const hours = parseFloat(document.getElementById('labor-hours').value) || 0;
    const rate = parseFloat(document.getElementById('hourly-rate').value) || 0;
    const laborTotal = hours * rate;
    const extraGear = parseFloat(document.getElementById('extra-gear-cost').value) || 0;
    const transportation = parseFloat(document.getElementById('transportation-cost').value) || 0;
    const assistantPay = parseFloat(document.getElementById('assistant-pay').value) || 0;
    const editingCost = parseFloat(document.getElementById('editing-cost').value) || 0;
    const additionalCost = parseFloat(document.getElementById('additional-cost').value) || 0;
    const margin = parseFloat(document.getElementById('margin-percentage').value) || 0;
    const additionalTotal = extraGear + transportation + assistantPay + editingCost + additionalCost;
    const subtotal = gearTotal + laborTotal + additionalTotal;
    const profit = subtotal * (margin / 100);
    const finalTotal = subtotal + profit;

    const params = new URLSearchParams({
        package_name: document.getElementById('package-name').value || 'New Project',
        client_name: document.getElementById('client-name').value || '',
        labor_hours: hours,
        hourly_rate: rate,
        extra_gear_cost: extraGear.toFixed(2),
        transportation_cost: transportation.toFixed(2),
        assistant_pay: assistantPay.toFixed(2),
        editing_cost: editingCost.toFixed(2),
        additional_cost: additionalCost.toFixed(2),
        margin_percentage: margin,
        equipment_total: gearTotal.toFixed(2),
        labor_total: laborTotal.toFixed(2),
        additional_costs_total: additionalTotal.toFixed(2),
        subtotal: subtotal.toFixed(2),
        margin_amount: profit.toFixed(2),
        final_total: finalTotal.toFixed(2),
        currency: state.currency,
        notes: document.getElementById('package-notes').value || '',
        equipment: JSON.stringify(state.selectedEquipment.map(e => ({
            name: e.name,
            model: e.model || '',
            type: e.type_name || 'Gear',
            quantity: e.quantity,
            unit_value: parseFloat(e.value).toFixed(2),
            total_value: (parseFloat(e.value) * e.quantity).toFixed(2)
        })))
    });
    window.open(`api/generate_pdf.php?${params.toString()}`, '_blank');
}

function downloadPackagePDF() {
    const gearTotal = state.selectedEquipment.reduce((sum, item) => sum + (parseFloat(item.value) * item.quantity), 0);
    const hours = parseFloat(document.getElementById('labor-hours').value) || 0;
    const rate = parseFloat(document.getElementById('hourly-rate').value) || 0;
    const laborTotal = hours * rate;
    const extraGear = parseFloat(document.getElementById('extra-gear-cost').value) || 0;
    const transportation = parseFloat(document.getElementById('transportation-cost').value) || 0;
    const assistantPay = parseFloat(document.getElementById('assistant-pay').value) || 0;
    const editingCost = parseFloat(document.getElementById('editing-cost').value) || 0;
    const additionalCost = parseFloat(document.getElementById('additional-cost').value) || 0;
    const margin = parseFloat(document.getElementById('margin-percentage').value) || 0;
    const additionalTotal = extraGear + transportation + assistantPay + editingCost + additionalCost;
    const subtotal = gearTotal + laborTotal + additionalTotal;
    const profit = subtotal * (margin / 100);
    const finalTotal = subtotal + profit;

    const params = new URLSearchParams({
        package_name: document.getElementById('package-name').value || 'New Project',
        client_name: document.getElementById('client-name').value || '',
        notes: document.getElementById('package-notes').value || '',
        final_total: finalTotal.toFixed(2),
        currency: state.currency,
        event_hours: hours.toFixed(1)
    });
    window.open(`api/generate_package_pdf.php?${params.toString()}`, '_blank');
}


