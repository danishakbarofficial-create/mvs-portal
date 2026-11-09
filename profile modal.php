<!-- Modal: My Profile & Password Management -->
<style>
  #modalMyProfile.open, #modalMyProfile.active {
    display: flex !important;
  }
  .profile-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 16px;
  }
  .profile-modal-box {
    background: #FFFFFF;
    border-radius: 12px;
    width: 100%;
    max-width: 520px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    overflow: hidden;
    animation: profileModalFadeIn 0.2s ease-out;
  }
  @keyframes profileModalFadeIn {
    from { opacity: 0; transform: translateY(-8px) scale(0.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
  }
  .profile-modal-header {
    padding: 18px 22px;
    border-bottom: 1px solid #E2E8F0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #F8FAFC;
  }
  .profile-modal-nav {
    display: flex;
    border-bottom: 1px solid #E2E8F0;
    background: #FFFFFF;
    padding: 0 22px;
  }
  .profile-nav-btn {
    padding: 12px 18px;
    background: none;
    border: none;
    border-bottom: 2px solid transparent;
    color: #64748B;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
  }
  .profile-nav-btn:hover {
    color: #16365C;
  }
  .profile-nav-btn.active {
    color: #16365C;
    border-bottom-color: #16365C;
  }
  .profile-modal-body {
    padding: 22px;
  }
  .profile-form-group {
    margin-bottom: 16px;
  }
  .profile-form-label {
    display: block;
    font-size: 0.82rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
  }
  .profile-form-input {
    width: 100%;
    padding: 9px 12px;
    border: 1px solid #CBD5E1;
    border-radius: 6px;
    font-size: 0.875rem;
    color: #1E293B;
    outline: none;
    transition: border-color 0.15s ease;
    box-sizing: border-box;
    font-family: inherit;
  }
  .profile-form-input:focus {
    border-color: #16365C;
    box-shadow: 0 0 0 3px rgba(22, 54, 92, 0.12);
  }
  .profile-pass-wrapper {
    position: relative;
    display: flex;
    align-items: center;
  }
  .profile-pass-toggle {
    position: absolute;
    right: 10px;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 0.95rem;
    color: #64748B;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .profile-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid #E2E8F0;
  }
  .profile-toast-container {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 100000;
    display: flex;
    flex-direction: column;
    gap: 8px;
    pointer-events: none;
  }
  .profile-toast-item {
    background: #0F172A;
    color: #FFFFFF;
    padding: 12px 18px;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
    gap: 10px;
    pointer-events: auto;
    animation: profileToastSlideIn 0.25s ease-out;
  }
  .profile-toast-success {
    border-left: 4px solid #10B981;
  }
  .profile-toast-error {
    border-left: 4px solid #EF4444;
  }
  @keyframes profileToastSlideIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>

<div class="profile-modal-overlay" id="modalMyProfile">
  <div class="profile-modal-box">
    <!-- Modal Header -->
    <div class="profile-modal-header">
      <div style="display:flex;align-items:center;gap:12px;">
        <div id="modalUserInitialsAvatar" style="width:40px;height:40px;border-radius:50%;background:#16365C;color:#FFFFFF;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.95rem;letter-spacing:0.5px;">--</div>
        <div>
          <h3 style="margin:0;font-size:1.05rem;color:#0F172A;font-weight:700;" id="modalUserTitleName">My Account Profile</h3>
          <div style="display:flex;gap:6px;align-items:center;margin-top:3px;">
            <span id="modalUserRoleDisplay" style="font-size:0.7rem;font-weight:700;padding:2px 8px;border-radius:4px;background:#E2E8F0;color:#334155;text-transform:uppercase;">--</span>
            <span style="font-size:0.75rem;color:#64748B;" id="modalUserUsernameDisplay">@username</span>
          </div>
        </div>
      </div>
      <button type="button" onclick="closeMyProfileModal()" style="background:none;border:none;font-size:1.4rem;color:#64748B;cursor:pointer;line-height:1;padding:4px;">&times;</button>
    </div>

    <!-- Segmented Tabs -->
    <div class="profile-modal-nav">
      <button type="button" class="profile-nav-btn active" id="btnProfileTabDetails" onclick="switchProfileTab('details')">
        Personal &amp; Contact Details
      </button>
      <button type="button" class="profile-nav-btn" id="btnProfileTabSecurity" onclick="switchProfileTab('security')">
        Change Password
      </button>
    </div>

    <!-- Tab 1: Personal & Contact Details -->
    <div id="tabProfileDetails" class="profile-modal-body">
      <form id="formProfileDetails" onsubmit="submitProfileDetails(event)">
        <div class="profile-form-group">
          <label class="profile-form-label">Full Name *</label>
          <input type="text" id="profInputName" class="profile-form-input" required placeholder="Enter your full name">
        </div>
        <div class="profile-form-group">
          <label class="profile-form-label">Official Email Address</label>
          <input type="email" id="profInputEmail" class="profile-form-input" placeholder="e.g. name@mvsksa.com">
        </div>
        <div class="profile-form-group">
          <label class="profile-form-label">Phone / Mobile Number</label>
          <input type="tel" id="profInputPhone" class="profile-form-input" placeholder="e.g. +966 50 123 4567">
        </div>
        <div class="profile-form-group" style="margin-bottom:0;">
          <label class="profile-form-label" style="display:flex;justify-content:space-between;align-items:center;">
            <span>Corporate Account ID</span>
            <span style="font-size:0.72rem;color:#94A3B8;font-weight:500;">(Institutional Identity - Locked)</span>
          </label>
          <input type="text" id="profInputUsername" class="profile-form-input" readonly disabled style="background:#F1F5F9;color:#64748B;cursor:not-allowed;">
        </div>

        <div class="profile-modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeMyProfileModal()" style="padding:8px 16px;border-radius:6px;font-size:0.85rem;cursor:pointer;">Cancel</button>
          <button type="submit" class="btn btn-primary" id="btnSaveProfDetails" style="padding:8px 18px;border-radius:6px;font-size:0.85rem;cursor:pointer;">Save Profile Changes</button>
        </div>
      </form>
    </div>

    <!-- Tab 2: Security & Password Management -->
    <div id="tabProfileSecurity" class="profile-modal-body" style="display:none;">
      <form id="formProfileSecurity" onsubmit="submitProfilePassword(event)">
        <div class="profile-form-group">
          <label class="profile-form-label">Current Password *</label>
          <div class="profile-pass-wrapper">
            <input type="password" id="profCurrentPassword" class="profile-form-input" required placeholder="Enter your current password">
            <button type="button" class="profile-pass-toggle" onclick="toggleProfilePasswordVis('profCurrentPassword', this)" title="Show/Hide">👁️</button>
          </div>
        </div>
        <div class="profile-form-group">
          <label class="profile-form-label">New Password * (Min. 6 characters)</label>
          <div class="profile-pass-wrapper">
            <input type="password" id="profNewPassword" class="profile-form-input" required minlength="6" placeholder="Enter new confidential password">
            <button type="button" class="profile-pass-toggle" onclick="toggleProfilePasswordVis('profNewPassword', this)" title="Show/Hide">👁️</button>
          </div>
        </div>
        <div class="profile-form-group" style="margin-bottom:0;">
          <label class="profile-form-label">Confirm New Password *</label>
          <div class="profile-pass-wrapper">
            <input type="password" id="profConfirmPassword" class="profile-form-input" required minlength="6" placeholder="Re-type new password to verify">
            <button type="button" class="profile-pass-toggle" onclick="toggleProfilePasswordVis('profConfirmPassword', this)" title="Show/Hide">👁️</button>
          </div>
        </div>

        <div class="profile-modal-footer">
          <button type="button" class="btn btn-secondary" onclick="closeMyProfileModal()" style="padding:8px 16px;border-radius:6px;font-size:0.85rem;cursor:pointer;">Cancel</button>
          <button type="submit" class="btn btn-primary" id="btnUpdateProfPassword" style="padding:8px 18px;border-radius:6px;font-size:0.85rem;cursor:pointer;">Update Password</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="profile-toast-container" id="profileToastContainer"></div>

<script>
// Open My Profile Modal
async function openMyProfileModal() {
  try {
    const res = await fetch('api/profile.php');
    const data = await res.json();
    if (!res.ok || !data.success) {
      showProfileNotification(data.message || 'Unable to load profile data', 'error');
      return;
    }

    const u = data.user;
    document.getElementById('profInputName').value = u.name || '';
    document.getElementById('profInputEmail').value = u.email || '';
    document.getElementById('profInputPhone').value = u.phone || '';
    document.getElementById('profInputUsername').value = u.username || '';

    document.getElementById('modalUserTitleName').textContent = u.name || 'My Account Profile';
    document.getElementById('modalUserUsernameDisplay').textContent = '@' + (u.username || '');

    // Initials Avatar
    const nameParts = (u.name || u.username || '').trim().split(' ');
    let initials = '';
    if (nameParts.length >= 2) {
      initials = nameParts[0][0] + nameParts[nameParts.length - 1][0];
    } else if (nameParts.length === 1 && nameParts[0].length > 0) {
      initials = nameParts[0].substring(0, 2);
    } else {
      initials = 'US';
    }
    document.getElementById('modalUserInitialsAvatar').textContent = initials.toUpperCase();

    // Role display
    const roleBadge = document.getElementById('modalUserRoleDisplay');
    roleBadge.textContent = (u.role || '').toUpperCase();
    if (u.role === 'admin') {
      roleBadge.style.background = '#F3E8FF';
      roleBadge.style.color = '#6B21A8';
    } else if (u.role === 'executive') {
      roleBadge.style.background = '#E2E8F0';
      roleBadge.style.color = '#1E293B';
    } else if (u.role === 'sales') {
      roleBadge.style.background = '#DBEAFE';
      roleBadge.style.color = '#1D4ED8';
    } else if (u.role === 'pm') {
      roleBadge.style.background = '#D1FAE5';
      roleBadge.style.color = '#047857';
    }

    switchProfileTab('details');
    document.getElementById('formProfileSecurity').reset();

    const modal = document.getElementById('modalMyProfile');
    modal.classList.add('open');
    modal.classList.add('active');
  } catch (err) {
    showProfileNotification(err.message, 'error');
  }
}

// Close Modal
function closeMyProfileModal() {
  const modal = document.getElementById('modalMyProfile');
  modal.classList.remove('open');
  modal.classList.remove('active');
}

// Close on backdrop click
document.getElementById('modalMyProfile')?.addEventListener('click', function(e) {
  if (e.target === this) {
    closeMyProfileModal();
  }
});

// Switch Tab
function switchProfileTab(tab) {
  const btnDetails = document.getElementById('btnProfileTabDetails');
  const btnSecurity = document.getElementById('btnProfileTabSecurity');
  const tabDetails = document.getElementById('tabProfileDetails');
  const tabSecurity = document.getElementById('tabProfileSecurity');

  if (tab === 'details') {
    btnDetails.classList.add('active');
    btnSecurity.classList.remove('active');
    tabDetails.style.display = 'block';
    tabSecurity.style.display = 'none';
  } else {
    btnSecurity.classList.add('active');
    btnDetails.classList.remove('active');
    tabSecurity.style.display = 'block';
    tabDetails.style.display = 'none';
  }
}

// Toggle password visibility
function toggleProfilePasswordVis(inputId, btn) {
  const input = document.getElementById(inputId);
  if (!input) return;
  if (input.type === 'password') {
    input.type = 'text';
    btn.textContent = '🙈';
  } else {
    input.type = 'password';
    btn.textContent = '👁️';
  }
}

// Submit Profile Details (Name, Email, Phone)
async function submitProfileDetails(e) {
  e.preventDefault();
  const btn = document.getElementById('btnSaveProfDetails');
  btn.disabled = true;
  btn.textContent = 'Saving...';

  const name = document.getElementById('profInputName').value.trim();
  const email = document.getElementById('profInputEmail').value.trim();
  const phone = document.getElementById('profInputPhone').value.trim();

  try {
    const res = await fetch('api/profile.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        action: 'update_profile',
        name: name,
        email: email,
        phone: phone
      })
    });
    const data = await res.json();
    if (res.ok && data.success) {
      showProfileNotification(data.message || 'Profile updated successfully!', 'success');
      document.getElementById('modalUserTitleName').textContent = name;

      // Update sidebar name dynamically if exists
      document.querySelectorAll('.user-mini-name').forEach(el => {
        el.textContent = name;
      });
      setTimeout(() => closeMyProfileModal(), 900);
    } else {
      showProfileNotification(data.message || 'Failed to update profile.', 'error');
    }
  } catch (err) {
    showProfileNotification(err.message, 'error');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Save Profile Changes';
  }
}

// Submit Password Change
async function submitProfilePassword(e) {
  e.preventDefault();
  const btn = document.getElementById('btnUpdateProfPassword');

  const curPass = document.getElementById('profCurrentPassword').value;
  const newPass = document.getElementById('profNewPassword').value;
  const confPass = document.getElementById('profConfirmPassword').value;

  if (newPass !== confPass) {
    showProfileNotification('New password and confirmation do not match.', 'error');
    return;
  }
  if (newPass.length < 6) {
    showProfileNotification('New password must be at least 6 characters long.', 'error');
    return;
  }

  btn.disabled = true;
  btn.textContent = 'Updating...';

  try {
    const res = await fetch('api/profile.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        action: 'change_password',
        current_password: curPass,
        new_password: newPass,
        confirm_password: confPass
      })
    });
    const data = await res.json();
    if (res.ok && data.success) {
      showProfileNotification(data.message || 'Password changed successfully!', 'success');
      document.getElementById('formProfileSecurity').reset();
      setTimeout(() => closeMyProfileModal(), 900);
    } else {
      showProfileNotification(data.message || 'Failed to update password.', 'error');
    }
  } catch (err) {
    showProfileNotification(err.message, 'error');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Update Password';
  }
}

// Notification Toast Helper
function showProfileNotification(msg, type = 'success') {
  const container = document.getElementById('profileToastContainer');
  if (!container) return;
  const toast = document.createElement('div');
  toast.className = 'profile-toast-item ' + (type === 'error' ? 'profile-toast-error' : 'profile-toast-success');
  toast.innerHTML = (type === 'error' ? '⚠️ ' : '✅ ') + escapeHtmlProf(msg);
  container.appendChild(toast);
  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transition = 'opacity 0.3s ease';
    setTimeout(() => toast.remove(), 300);
  }, 3500);
}

function escapeHtmlProf(str) {
  if (!str) return '';
  return String(str).replace(/[&<>"']/g, m => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
  })[m]);
}
</script>
