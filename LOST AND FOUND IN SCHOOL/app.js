// Campus Lost & Found - Interactive Client-Side Storage & Logic (No SQL)

const DEFAULT_USERS = [
  { id: 1, name: "Campus Administrator", email: "admin@campus.edu", password: "admin123", role: "Admin" },
  { id: 2, name: "Security Office Staff", email: "staff@campus.edu", password: "staff123", role: "Staff" },
  { id: 3, name: "Alex Johnson", email: "student@campus.edu", password: "student123", role: "User" }
];

const DEFAULT_ITEMS = [
  {
    id: 101,
    type: "Lost",
    name: "MacBook Air M2 (Silver)",
    category: "Electronics",
    description: "Silver 13-inch MacBook inside a dark grey felt sleeve. Has a small NASA sticker on the bottom lid.",
    date: "2026-09-28",
    location: "Main Library - 2nd Floor Quiet Zone",
    photo: "https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600&auto=format&fit=crop&q=60",
    status: "Pending Verification",
    reported_by: "student@campus.edu",
    created_at: "2026-09-28 14:20"
  },
  {
    id: 102,
    type: "Found",
    name: "Casio Scientific Calculator fx-991EX",
    category: "Electronics",
    description: "Black and white casing with slide-on cover. Left on desk row 4 after calculus class.",
    date: "2026-09-30",
    location: "Science Complex - Lecture Hall 3",
    photo: "https://images.unsplash.com/photo-1594980596870-8aa52a78d8cd?w=600&auto=format&fit=crop&q=60",
    status: "Available for Claim",
    reported_by: "staff@campus.edu",
    created_at: "2026-09-30 16:45"
  },
  {
    id: 103,
    type: "Lost",
    name: "Student ID & Dorm Key Card",
    category: "IDs & Cards",
    description: "Blue University lanyard holding Student ID (Alex Johnson) and a white RFID dorm door access card.",
    date: "2026-10-01",
    location: "Campus Dining Hall / Cafeteria",
    photo: "https://images.unsplash.com/photo-1578873375972-014495cfa3e3?w=600&auto=format&fit=crop&q=60",
    status: "Pending Verification",
    reported_by: "student@campus.edu",
    created_at: "2026-10-01 12:10"
  },
  {
    id: 104,
    type: "Found",
    name: "Navy Blue Hydro Flask (32oz)",
    category: "Other",
    description: "Dark blue insulated water bottle with national park stickers and a black flex cap.",
    date: "2026-10-02",
    location: "Campus Recreation Center Gym",
    photo: "https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=600&auto=format&fit=crop&q=60",
    status: "Claim Approved",
    reported_by: "staff@campus.edu",
    created_at: "2026-10-02 09:30"
  },
  {
    id: 105,
    type: "Found",
    name: "AirPods Pro (2nd Gen) in Black Case",
    category: "Electronics",
    description: "Apple AirPods inside a matte black silicone protective case with small clip.",
    date: "2026-10-02",
    location: "Student Union Lounge Benches",
    photo: "https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?w=600&auto=format&fit=crop&q=60",
    status: "Available for Claim",
    reported_by: "staff@campus.edu",
    created_at: "2026-10-02 17:15"
  },
  {
    id: 106,
    type: "Lost",
    name: "Organic Chemistry 8th Ed. Textbook",
    category: "Books & Stationery",
    description: "Hardcover textbook with sticky notes on chapters 4 and 5. Yellow highlighter on edge.",
    date: "2026-09-29",
    location: "Engineering Hall - Study Room 204",
    photo: "https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=600&auto=format&fit=crop&q=60",
    status: "Pending Verification",
    reported_by: "student@campus.edu",
    created_at: "2026-09-29 18:00"
  }
];

const DEFAULT_CLAIMS = [
  {
    id: 201,
    item_id: 104,
    item_name: "Navy Blue Hydro Flask (32oz)",
    claimant_email: "student@campus.edu",
    claimant_name: "Alex Johnson",
    proof_details: "Bottle has a Yosemite National Park sticker with a tiny dent on the lower rim. I can identify all stickers.",
    status: "Approved",
    admin_notes: "Verified sticker description with item at Security Desk. Ready for pickup.",
    created_at: "2026-10-02 11:20"
  },
  {
    id: 202,
    item_id: 105,
    item_name: "AirPods Pro (2nd Gen) in Black Case",
    claimant_email: "student@campus.edu",
    claimant_name: "Alex Johnson",
    proof_details: "My iPhone name is \"Alex Johnson Airpods\" and pairs automatically. Case has carabiner ring.",
    status: "Pending",
    admin_notes: "Under review by staff. Awaiting Bluetooth pairing confirmation.",
    created_at: "2026-10-02 18:40"
  }
];

const DEFAULT_NOTIFS = [
  {
    id: 1,
    user_email: "student@campus.edu",
    title: "Claim Approved!",
    message: "Your claim for \"Navy Blue Hydro Flask (32oz)\" was approved. Please collect it from the Campus Security Office.",
    type: "success",
    created_at: "2026-10-02 14:15"
  },
  {
    id: 2,
    user_email: "student@campus.edu",
    title: "Claim Under Review",
    message: "Staff is currently verifying your claim for \"AirPods Pro (2nd Gen)\".",
    type: "info",
    created_at: "2026-10-02 18:45"
  },
  {
    id: 3,
    user_email: "student@campus.edu",
    title: "Lost Item Published",
    message: "Your report for \"MacBook Air M2\" has been published to the campus search board.",
    type: "primary",
    created_at: "2026-09-28 14:30"
  }
];

// Initialize Storage
function initStorage() {
  if (!localStorage.getItem('clf_users')) {
    localStorage.setItem('clf_users', JSON.stringify(DEFAULT_USERS));
  }
  if (!localStorage.getItem('clf_items')) {
    localStorage.setItem('clf_items', JSON.stringify(DEFAULT_ITEMS));
  }
  if (!localStorage.getItem('clf_claims')) {
    localStorage.setItem('clf_claims', JSON.stringify(DEFAULT_CLAIMS));
  }
  if (!localStorage.getItem('clf_notifs')) {
    localStorage.setItem('clf_notifs', JSON.stringify(DEFAULT_NOTIFS));
  }
}

initStorage();

// Storage Helpers
function getUsers() { return JSON.parse(localStorage.getItem('clf_users') || '[]'); }
function saveUsers(u) { localStorage.setItem('clf_users', JSON.stringify(u)); }

function getItems() { return JSON.parse(localStorage.getItem('clf_items') || '[]'); }
function saveItems(i) { localStorage.setItem('clf_items', JSON.stringify(i)); }

function getClaims() { return JSON.parse(localStorage.getItem('clf_claims') || '[]'); }
function saveClaims(c) { localStorage.setItem('clf_claims', JSON.stringify(c)); }

function getNotifs() { return JSON.parse(localStorage.getItem('clf_notifs') || '[]'); }
function saveNotifs(n) { localStorage.setItem('clf_notifs', JSON.stringify(n)); }

function getCurrentUser() {
  const u = localStorage.getItem('clf_current_user');
  return u ? JSON.parse(u) : null;
}

function setCurrentUser(user) {
  if (user) {
    localStorage.setItem('clf_current_user', JSON.stringify(user));
  } else {
    localStorage.removeItem('clf_current_user');
  }
}

function logoutUser() {
  localStorage.removeItem('clf_current_user');
  window.location.href = 'login.html';
}

// Render dynamic navbar
function renderNavbar(activePage) {
  const user = getCurrentUser();
  const navContainer = document.getElementById('navbarContainer');
  if (!navContainer) return;

  let authLinks = '';
  if (user) {
    let roleClass = 'badge-role-user';
    if (user.role === 'Admin') roleClass = 'badge-role-admin';
    if (user.role === 'Staff') roleClass = 'badge-role-staff';

    authLinks = `
      <span class="badge ${roleClass} px-2 py-2">
        <i class="bi bi-person-fill"></i> ${user.name} (${user.role})
      </span>
      <button onclick="logoutUser()" class="btn btn-outline-light btn-sm ms-2">
        <i class="bi bi-box-arrow-right"></i> Logout
      </button>
    `;
  } else {
    authLinks = `
      <a href="login.html" class="btn btn-light btn-sm fw-semibold">
        <i class="bi bi-box-arrow-in-right"></i> Login
      </a>
      <a href="register.html" class="btn btn-outline-light btn-sm ms-2">
        <i class="bi bi-person-plus"></i> Register
      </a>
    `;
  }

  let adminStaffNav = '';
  if (user && (user.role === 'Admin' || user.role === 'Staff')) {
    adminStaffNav = `
      <li class="nav-item">
        <a class="nav-link ${activePage === 'admin' ? 'active' : ''}" href="admin.html">
          <i class="bi bi-shield-lock me-1"></i> ${user.role === 'Admin' ? 'Admin Panel' : 'Staff Panel'}
        </a>
      </li>
    `;
  }

  let userDashboardNav = '';
  if (user) {
    userDashboardNav = `
      <li class="nav-item">
        <a class="nav-link ${activePage === 'dashboard' ? 'active' : ''}" href="dashboard.html">
          <i class="bi bi-speedometer2 me-1"></i> Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link ${activePage === 'claims' ? 'active' : ''}" href="claims.html">
          <i class="bi bi-file-earmark-check me-1"></i> My Claims
        </a>
      </li>
    `;
  }

  navContainer.innerHTML = `
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.html">
          <i class="bi bi-compass fs-4"></i>
          <span>Campus Lost &amp; Found</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link ${activePage === 'index' ? 'active' : ''}" href="index.html">
                <i class="bi bi-house-door me-1"></i> Home
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link ${activePage === 'search' ? 'active' : ''}" href="search.html">
                <i class="bi bi-search me-1"></i> Search Items
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link ${activePage === 'report-lost' ? 'active' : ''}" href="report-lost.html">
                <i class="bi bi-exclamation-circle me-1"></i> Report Lost
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link ${activePage === 'report-found' ? 'active' : ''}" href="report-found.html">
                <i class="bi bi-check2-circle me-1"></i> Report Found
              </a>
            </li>
            ${userDashboardNav}
            ${adminStaffNav}
          </ul>
          <div class="d-flex align-items-center">
            ${authLinks}
          </div>
        </div>
      </div>
    </nav>
  `;
}

// Render dynamic footer
function renderFooter() {
  const footerContainer = document.getElementById('footerContainer');
  if (!footerContainer) return;
  footerContainer.innerHTML = `
    <footer class="mt-auto">
      <div class="container">
        <div class="row gy-3 align-items-center">
          <div class="col-md-6 text-center text-md-start">
            <h6 class="text-white mb-1"><i class="bi bi-compass"></i> Campus Lost &amp; Found Portal</h6>
            <p class="small text-secondary mb-0">
              Helping students and staff reunite with their belongings quickly and securely.
            </p>
          </div>
          <div class="col-md-6 text-center text-md-end">
            <div class="d-inline-flex gap-3 small mb-1">
              <a href="index.html">Home</a>
              <a href="search.html">Search Items</a>
              <a href="report-lost.html">Report Lost</a>
              <a href="report-found.html">Report Found</a>
              <a href="login.html">Login</a>
            </div>
            <p class="small text-secondary mb-0">&copy; ${new Date().getFullYear()} College Campus Security &amp; Student Affairs.</p>
          </div>
        </div>
      </div>
    </footer>
  `;
}
