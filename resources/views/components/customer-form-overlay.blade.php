<div id="customerFormOverlay" class="customer-form-overlay">
    <div class="customer-form">
        <button type="button" id="customerFormClose" class="close-btn" onclick="closeCustomerForm()">×</button>
        <h2>Contact details</h2>
        <form id="customerForm">
            <label>
                <span>Name</span>
                <input type="text" name="customerName" required />
            </label>
            <label>
                <span>Mobile number</span>
                <input type="tel" name="customerPhone" required />
            </label>
            <label>
                <span>Address</span>
                <textarea name="customerAddress" rows="3" required></textarea>
            </label>
            <button type="submit" class="primary-btn">Save details</button>
        </form>
    </div>
</div>

