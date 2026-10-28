# Campus Lost and Found Web Portal

A clean, modern, and beginner-friendly **Campus Lost and Found Web Portal** designed for university and college environments.

Works seamlessly on:
- **GitHub Pages** (Free 1-click cloud hosting with zero server setup)
- **Direct Browser / Offline** (Double-click `index.html`)
- **PHP / XAMPP** (Saved in `php_version/` with JSON data handling and zero SQL)

---

## 🚀 How to Deploy on GitHub Pages (Instant Run)

1. Open your GitHub repository (e.g. `https://github.com/justinecostillas09-create/...`).
2. Upload or drag-and-drop the contents of this folder into the root of your repository.
3. In your GitHub repository:
   - Go to **Settings** (top tab)
   - Click **Pages** (in the left menu)
   - Under **Build and deployment > Branch**, choose **`main`** (or `master`) and folder **`/(root)`**, then click **Save**.
4. Wait 1 to 2 minutes. Your website will be live at:
   - If repo name is `justinecostillas09-create.github.io`:  
     `https://justinecostillas09-create.github.io/`
   - If repo name is `LOST-AND-FOUND` (or another name):  
     `https://justinecostillas09-create.github.io/<your-repo-name>/`

---

## 📁 Root Structure (GitHub Pages Ready)

```text
LOST AND FOUND IN SCHOOL/
│
├── index.html                 # 1. Home Page (Hero, statistics, recent cards)
├── login.html                 # 2. Login Page
├── register.html              # 3. Register Page
├── dashboard.html             # 4. Dashboard (Lost, Found, Claims & Notifications)
├── report-lost.html           # 5. Report Lost Item (Photo preview, location, category)
├── report-found.html          # 6. Report Found Item (Photo preview, storage info)
├── search.html                # 7. Search Items (Keyword, category & location filters)
├── item-details.html          # 8. Item Details (Full info & Claim modal)
├── claims.html                # 9. My Claims (Submitted claims & status tracking)
├── admin.html                 # 10. Admin Dashboard (Approve/Reject claims, update items)
├── app.js                     # Client storage controller (localStorage with sample data)
├── .nojekyll                  # Ensures GitHub Pages serves all static assets correctly
│
├── assets/
│   ├── css/style.css          # Modern campus styling
│   └── js/main.js             # Client helpers
│
└── php_version/               # Full PHP/Laravel-friendly version (for school PHP submission)
```

---

## 👥 User Roles & Demo Credentials

| Role | Email | Password | Permissions |
|---|---|---|---|
| **Admin** | `admin@campus.edu` | `admin123` | Full access (Manage users, items, claims, update statuses) |
| **Staff** | `staff@campus.edu` | `staff123` | Manage found items, verify items, approve/reject claims |
| **User** | `student@campus.edu` | `student123` | Report items, browse/search, submit claims, view dashboard |
