<div class="modal" id="renameTeamModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Rename Team</h3>
            <button class="modal-close" onclick="closeRenameModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Team Name</label>
                <input type="text" id="newTeamName" placeholder="Enter new team name" value="">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeRenameModal()">Cancel</button>
            <button class="btn-primary" onclick="confirmRename()">Rename</button>
        </div>
    </div>
</div>

<script>
    function showRenameModal() {
        document.getElementById('newTeamName').value = teamData.name;
        document.getElementById('renameTeamModal').classList.add('show');
    }
    
    function closeRenameModal() {
        document.getElementById('renameTeamModal').classList.remove('show');
    }
    
    async function confirmRename() {
        const newName = document.getElementById('newTeamName').value.trim();
        if (!newName) {
            alert('Please enter a team name');
            return;
        }
        
        await renameTeam(newName);
        closeRenameModal();
    }
</script>

