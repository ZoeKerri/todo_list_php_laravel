<div class="modal" id="confirmDialogModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="confirmDialogTitle">Confirmation</h3>
            <button class="modal-close" onclick="closeConfirmDialog()">&times;</button>
        </div>
        <div class="modal-body">
            <p id="confirmDialogMessage">Are you sure?</p>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeConfirmDialog()">Cancel</button>
            <button class="btn-primary" id="confirmDialogBtn" onclick="executeConfirmAction()">Confirm</button>
        </div>
    </div>
</div>

<script>
    let confirmAction = null;
    
    function showConfirmDialog(title, message, onConfirm) {
        document.getElementById('confirmDialogTitle').textContent = title || 'Confirmation';
        document.getElementById('confirmDialogMessage').textContent = message;
        confirmAction = onConfirm;
        document.getElementById('confirmDialogModal').classList.add('show');
    }
    
    function closeConfirmDialog() {
        document.getElementById('confirmDialogModal').classList.remove('show');
        confirmAction = null;
    }
    
    function executeConfirmAction() {
        if (confirmAction) {
            confirmAction();
        }
        closeConfirmDialog();
    }
</script>

