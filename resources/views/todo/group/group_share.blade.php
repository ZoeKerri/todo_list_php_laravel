@extends('layouts.app')

@section('title', 'Share Team')

@push('styles')
<style>
    .content-header {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
        padding: 15px 0;
    }
    .content-header .title {
        font-size: 1.2rem;
        font-weight: 600;
        margin: 0;
        color: var(--text-primary);
    }
    .content-header a {
        color: var(--text-primary);
        font-size: 1.2rem;
        text-decoration: none;
    }
    
    .qr-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 60vh;
        background-color: var(--card-bg);
        border-radius: 12px;
        padding: 40px;
        margin: 20px 0;
    }
    .qr-code {
        background-color: var(--bg-primary);
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        transition: background-color 0.3s ease;
    }
    .qr-code img {
        display: block;
        max-width: 300px;
        height: auto;
    }
    .team-name {
        font-size: 1.2rem;
        color: var(--text-muted);
        margin-bottom: 10px;
        text-align: center;
    }
    .team-instruction {
        font-size: 0.95rem;
        color: var(--text-muted);
        margin-bottom: 25px;
        text-align: center;
    }
    .share-actions {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }
    .share-btn {
        padding: 12px 24px;
        background-color: var(--accent-color);
        color: var(--text-primary);
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s ease, color 0.3s ease;
        font-weight: 600;
        transition: background-color 0.2s ease;
    }
    .share-btn:hover {
        opacity: 0.9;
    }
    .share-btn.secondary {
        background-color: var(--bg-tertiary);
        color: var(--text-primary);
    }
    
    .loading {
        text-align: center;
        padding: 40px;
        color: var(--text-muted);
    }
</style>
@endpush

@section('content')

<div class="content-header">
    <a href="{{ url('/group/' . ($id ?? '')) }}"><i class="fas fa-arrow-left"></i></a>
    <h2 class="title" id="pageTitle">Share Team</h2>
</div>

<div id="loadingState" class="loading">
    <i class="fas fa-spinner fa-spin"></i>
    <p>Loading team data...</p>
</div>

<div id="contentArea" style="display: none;">
    <div class="qr-container">
        <div class="team-name" id="teamName">Loading...</div>
        <div class="team-instruction">Quét mã QR để tham gia nhóm</div>
        <div class="qr-code">
            <div id="qrcode"></div>
        </div>
        <div class="share-actions">
            <button class="share-btn" onclick="downloadQR()">
                <i class="fas fa-download"></i> Tải mã QR
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    const teamId = {{ $id ?? 'null' }};
    let teamCode = '';
    
    document.addEventListener('DOMContentLoaded', function() {
        loadTeamData();
    });
    
    async function loadTeamData() {
        try {
            const apiToken = getApiToken();
            const response = await fetch(`/api/v1/team/detail/${teamId}`, {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            if (response.ok && result.status === 200) {
                const team = result.data;
                teamCode = team.code || `TODOLIST-${team.id}`;
                
                document.getElementById('teamName').textContent = team.name;
                const backLink = document.querySelector('.content-header a');
                if (backLink) {
                    backLink.href = `/group/${team.id}`;
                }
                // Generate QR code
                new QRCode(document.getElementById('qrcode'), {
                    text: teamCode,
                    width: 256,
                    height: 256,
                    colorDark: '#000000',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });
                
                document.getElementById('loadingState').style.display = 'none';
                document.getElementById('contentArea').style.display = 'block';
            } else {
                throw new Error(result.message || 'Failed to load team');
            }
        } catch (error) {
            console.error('Error loading team:', error);
            document.getElementById('loadingState').innerHTML = `
                <i class="fas fa-exclamation-triangle"></i>
                <p>Error loading team: ${error.message}</p>
            `;
        }
    }
    
    function getApiToken() {
        const sessionToken = '{{ session("jwt_token") }}';
        if (sessionToken && sessionToken !== '' && sessionToken !== 'null') {
            return sessionToken;
        }
        const localToken = localStorage.getItem('access_token');
        if (localToken) {
            return localToken;
        }
        return null;
    }
    
    function downloadQR() {
        const qrCanvas = document.querySelector('#qrcode canvas');
        if (qrCanvas) {
            const url = qrCanvas.toDataURL();
            const link = document.createElement('a');
            link.download = `team_qr_${teamId}_${Date.now()}.png`;
            link.href = url;
            link.click();
        }
    }
</script>
@endpush

