<div class="modal" id="chooseNewLeaderModal">
    <div class="modal-content choose-leader-modal">
        <div class="modal-header">
            <h3>Select New Team Leader</h3>
            <button class="modal-close" onclick="closeChooseNewLeaderModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p class="helper-text">
                Please select another member to transfer leadership to before you leave.
            </p>
            <div class="leader-search">
                <div class="search-input-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" id="newLeaderSearch" placeholder="Search by name or email">
                </div>
                <div class="search-meta" id="leaderSearchMeta"></div>
            </div>
            <div class="candidate-list" id="leaderCandidateList">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeChooseNewLeaderModal()">Close</button>
            <button class="btn-primary" id="confirmNewLeaderBtn" onclick="confirmNewLeader()" disabled>
                <i class="fas fa-check"></i> Confirm Transfer
            </button>
        </div>
    </div>
</div>

<style>
    .choose-leader-modal {
        padding: 28px;
        border-radius: 16px;
        background: var(--card-bg);
        max-width: 520px;
        width: 100%;
    }
    .choose-leader-modal .modal-header {
        padding: 0 0 16px 0;
        border-bottom: 1px solid var(--border-color);
    }
    .choose-leader-modal .modal-header h3 {
        margin: 0;
        font-size: 1.4rem;
        color: var(--text-primary);
    }
    .choose-leader-modal .modal-body {
        padding: 20px 0;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .leader-search {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .search-input-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 10px 14px;
        background-color: var(--bg-secondary);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .search-input-wrapper:focus-within {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.12);
    }
    .search-input-wrapper i {
        color: var(--text-muted);
        font-size: 0.95rem;
    }
    .search-input-wrapper input {
        border: none;
        outline: none;
        background: transparent;
        width: 100%;
        color: var(--text-primary);
        font-size: 1rem;
    }
    .search-meta {
        font-size: 0.85rem;
        color: var(--text-muted);
        min-height: 18px;
    }
    .choose-leader-modal .helper-text {
        margin: 0;
        font-size: 0.95rem;
        color: var(--text-muted);
    }
    .candidate-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-height: 320px;
        overflow-y: auto;
        padding-right: 6px;
    }
    .leader-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        background-color: var(--bg-secondary);
        cursor: pointer;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .leader-card:hover {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.12);
    }
    .leader-card.selected {
        border-color: var(--accent-color);
        background-color: rgba(124, 58, 237, 0.08);
        box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.12);
    }
    .leader-card .leader-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        overflow: hidden;
        background: var(--accent-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-primary);
        transition: background-color 0.3s ease, color 0.3s ease;
        font-weight: 600;
        flex-shrink: 0;
    }
    .leader-card .leader-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .leader-card .leader-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .leader-card .leader-name {
        font-weight: 600;
        color: var(--text-primary);
    }
    .leader-card .leader-email {
        font-size: 0.9rem;
        color: var(--text-muted);
    }
    .leader-card .leader-role {
        font-size: 0.8rem;
        color: var(--accent-color);
        font-weight: 600;
    }
    .leader-card .checkmark {
        font-size: 1.2rem;
        color: var(--accent-color);
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .leader-card.selected .checkmark {
        opacity: 1;
    }
    .choose-leader-modal .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding: 20px 0 0 0;
        border-top: 1px solid var(--border-color);
    }
    .choose-leader-modal .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .no-candidate {
        text-align: center;
        padding: 30px 20px;
        color: var(--text-muted);
    }
    .no-candidate i {
        font-size: 2rem;
        margin-bottom: 12px;
        display: block;
    }
    @media (max-width: 600px) {
        .choose-leader-modal {
            padding: 20px;
        }
    }
</style>

<script>
    function showChooseNewLeaderModal() {
        const eligibleMembers = allMembers.filter(m => m.userId !== userId);
        
        if (eligibleMembers.length === 0) {
            if (confirm('No other members available. Do you want to disband the team?')) {
                disbandTeam();
            }
            return;
        }
        
        const listContainer = document.getElementById('leaderCandidateList');
        const confirmBtn = document.getElementById('confirmNewLeaderBtn');
        confirmBtn.disabled = true;
        confirmBtn.dataset.memberId = '';

        window.leaderCandidates = eligibleMembers;
        window.filteredLeaderCandidates = [...eligibleMembers];

        const searchInput = document.getElementById('newLeaderSearch');
        if (searchInput) {
            searchInput.value = '';
            searchInput.oninput = function(e) {
                filterLeaderCandidates(e.target.value);
            };
        }

        renderLeaderCandidates(filteredLeaderCandidates);

        document.getElementById('chooseNewLeaderModal').classList.add('show');
    }

    function filterLeaderCandidates(query) {
        const normalized = (query || '').trim().toLowerCase();
        if (!Array.isArray(window.leaderCandidates)) {
            window.filteredLeaderCandidates = [];
        } else if (!normalized) {
            window.filteredLeaderCandidates = [...window.leaderCandidates];
        } else {
            window.filteredLeaderCandidates = window.leaderCandidates.filter(member => {
                const name = (member.user?.name || '').toLowerCase();
                const email = (member.user?.email || '').toLowerCase();
                return name.includes(normalized) || email.includes(normalized);
            });
        }

        renderLeaderCandidates(window.filteredLeaderCandidates, normalized);
    }

    function renderLeaderCandidates(candidates = [], query = '') {
        const listContainer = document.getElementById('leaderCandidateList');
        const confirmBtn = document.getElementById('confirmNewLeaderBtn');
        const meta = document.getElementById('leaderSearchMeta');
        if (!listContainer || !confirmBtn) return;

        const selectedId = confirmBtn.dataset.memberId ? parseInt(confirmBtn.dataset.memberId, 10) : null;

        if (!Array.isArray(candidates) || candidates.length === 0) {
            listContainer.innerHTML = `
                <div class="no-candidate">
                    <i class="fas fa-user-slash"></i>
                    <p>${query ? 'No matching members found.' : 'No eligible members to transfer leadership to.'}</p>
                    <span>${query ? 'Try a different search term.' : 'You can choose to disband the team instead.'}</span>
                </div>
            `;
            confirmBtn.disabled = true;
            confirmBtn.dataset.memberId = '';
        } else {
            listContainer.innerHTML = candidates.map(member => {
                const name = escapeHtml(member.user?.name || 'No name');
                const email = escapeHtml(member.user?.email || 'No email');
                const avatar = renderAvatarHTML(member.user);
                const isSelected = selectedId && selectedId === member.id;
                const displayName = email && email !== 'No email' 
                    ? `${name} (${email})` 
                    : name;
                return `
                    <div class="leader-card ${isSelected ? 'selected' : ''}" data-member-id="${member.id}" onclick="selectLeaderCandidate(${member.id})">
                        <div class="leader-avatar">${avatar}</div>
                        <div class="leader-info">
                            <span class="leader-name">${displayName}</span>
                            <span class="leader-role">Member</span>
                        </div>
                        <i class="fas fa-check checkmark"></i>
                    </div>
                `;
            }).join('');

            if (!selectedId || !candidates.some(member => member.id === selectedId)) {
                confirmBtn.disabled = true;
                confirmBtn.dataset.memberId = '';
            }
        }

        if (meta) {
            if (!query) {
                meta.textContent = '';
            } else {
                meta.textContent = candidates.length === 0
                    ? 'No matching members found'
                    : `Found ${candidates.length} members`;
            }
        }
    }
    
    function closeChooseNewLeaderModal() {
        const modal = document.getElementById('chooseNewLeaderModal');
        if (!modal) return;
        modal.classList.remove('show');
        const listContainer = document.getElementById('leaderCandidateList');
        if (listContainer) listContainer.innerHTML = '';
        const confirmBtn = document.getElementById('confirmNewLeaderBtn');
        confirmBtn.disabled = true;
        confirmBtn.dataset.memberId = '';
        const searchInput = document.getElementById('newLeaderSearch');
        if (searchInput) {
            searchInput.value = '';
            searchInput.oninput = null;
        }
        const meta = document.getElementById('leaderSearchMeta');
        if (meta) meta.textContent = '';
        window.leaderCandidates = [];
        window.filteredLeaderCandidates = [];
    }
    
    async function confirmNewLeader() {
        const confirmBtn = document.getElementById('confirmNewLeaderBtn');
        const memberId = parseInt(confirmBtn.dataset.memberId, 10);
        if (!memberId) return;
        
        const apiToken = getApiToken();
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        
        try {
            const response = await fetch(`/api/v1/member/`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id: memberId,
                    role: 'LEADER'
                })
            });
            
            const result = await response.json();
            if (response.ok && result.status === 200) {
                await deleteMember(currentUserMember.id);
                alert('New leader assigned. You have left the team.');
                window.location.href = '/group';
            } else {
                throw new Error(result.message || 'Failed to assign new leader');
            }
        } catch (error) {
            console.error('Error assigning new leader:', error);
            alert('Error assigning new leader: ' + error.message);
        } finally {
            if (confirmBtn) {
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="fas fa-check"></i> Confirm Transfer';
            }
        }
    }

    function selectLeaderCandidate(memberId) {
        const cards = document.querySelectorAll('.leader-card');
        cards.forEach(card => card.classList.remove('selected'));
        const selectedCard = document.querySelector(`.leader-card[data-member-id="${memberId}"]`);
        if (selectedCard) {
            selectedCard.classList.add('selected');
            const confirmBtn = document.getElementById('confirmNewLeaderBtn');
            confirmBtn.disabled = false;
            confirmBtn.dataset.memberId = memberId;
        }
    }

    function renderAvatarHTML(user) {
        const avatar = user?.avatar;
        const name = user?.name || user?.email || 'U';
        const fallback = (name || 'U')[0].toUpperCase();
        if (!avatar) {
            return `<span class="avatar-initial">${fallback}</span>`;
        }
        const avatarUrl = avatar.startsWith('http') ? avatar : `/storage/${avatar}`;
        return `<img src="${avatarUrl}" alt="${fallback}" onerror="this.remove(); this.parentElement.innerHTML='<span class=\'avatar-initial\'>${fallback}</span>';">`;
    }
</script>

