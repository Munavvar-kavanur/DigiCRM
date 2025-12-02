# 🛠 Developer Notes & Troubleshooting

**⚠️ INTERNAL USE ONLY - Do not include in CodeCanyon distribution ⚠️**

## 🔧 Common Issues & Fixes

### 1. Migration Error: "Table 'personal_access_tokens' already exists"
**Scenario:**
Happens when pulling updates to a server where the database already has tables, but a new migration file was created (e.g., via a merge conflict or package update).

**Error:**
`SQLSTATE[42S01]: Base table or view already exists: 1050 Table 'personal_access_tokens' already exists`

**Solution:**
1. Check for duplicate migration files in `database/migrations/`.
2. If a duplicate exists (e.g., `2025_12_01_064457_...`), delete it:
   ```bash
   rm database/migrations/2025_12_01_064457_create_personal_access_tokens_table.php
   ```
3. Run migrations again:
   ```bash
   php artisan migrate
   ```

### 2. Chat Error: "Table 'conversations' doesn't exist"
**Scenario:**
Happens if the migration process failed halfway (e.g., due to the error above), causing subsequent tables (like `conversations`) to not be created.

**Solution:**
1. Fix the blocking error (see Issue #1).
2. Re-run migrations to create the missing tables.

---

## 📦 Pre-Release Checklist (CodeCanyon)

Before zipping for sale:
1. [ ] **Delete** this `DEV_NOTES.md` file.
2. [ ] **Delete** `.git` folder.
3. [ ] **Delete** `node_modules` folder (users run `npm install`).
4. [ ] **Delete** `vendor` folder (users run `composer install`).
5. [ ] **Clear** `storage/logs/*.log`.
6. [ ] **Reset** `.env` to `.env.example`.
7. [ ] **Ensure** `documentation/index.html` is up to date.
