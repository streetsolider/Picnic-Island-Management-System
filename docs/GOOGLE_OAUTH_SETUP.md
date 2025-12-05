# Google OAuth Setup Guide

Complete guide for setting up Google OAuth authentication for the Picnic Island Management System.

---

## Table of Contents

- [Prerequisites](#prerequisites)
- [Step 1: Create Google Cloud Project](#step-1-create-google-cloud-project)
- [Step 2: Configure OAuth Consent Screen](#step-2-configure-oauth-consent-screen)
- [Step 3: Create OAuth Credentials](#step-3-create-oauth-credentials)
- [Step 4: Configure Laravel Application](#step-4-configure-laravel-application)
- [Adding Test Users](#adding-test-users)
- [Managing Test Users](#managing-test-users)
- [Environment-Specific Setup](#environment-specific-setup)
- [Troubleshooting](#troubleshooting)
- [Security Best Practices](#security-best-practices)

---

## Prerequisites

- Google Account (Gmail)
- Access to [Google Cloud Console](https://console.cloud.google.com/)
- Project running on `http://localhost` (or your development URL)

---

## Step 1: Create Google Cloud Project

### 1.1 Access Google Cloud Console

1. Go to: **https://console.cloud.google.com/**
2. Sign in with your Google account

### 1.2 Create New Project

1. Click the **project dropdown** in the top navigation bar (next to "Google Cloud")
2. Click **"New Project"** button
3. Fill in project details:
   - **Project name**: `Picnic Island Dev` (or any descriptive name)
   - **Organization**: Leave as default (unless you have a Google Workspace)
4. Click **"Create"**
5. Wait for the project to be created (takes ~10 seconds)
6. **Select your new project** from the project dropdown

---

## Step 2: Configure OAuth Consent Screen

The OAuth consent screen is what users see when they sign in with Google.

### 2.1 Navigate to OAuth Consent Screen

1. In the left sidebar, go to: **APIs & Services** → **OAuth consent screen**
2. Or search for "OAuth consent screen" in the top search bar

### 2.2 Choose User Type

1. Select **"External"**
   - Choose this unless you have a Google Workspace organization
   - Allows any Google account to sign in
2. Click **"Create"**

### 2.3 Fill in App Information

#### **App Information Section:**

| Field | Value |
|-------|-------|
| **App name** | `Picnic Island` or `Picnic Island (Development)` |
| **User support email** | Your Gmail address (required) |
| **App logo** | Optional (you can add later) |

#### **App Domain Section:**

| Field | Value |
|-------|-------|
| **Application home page** | `http://localhost` (for development) |
| **Application privacy policy link** | Leave blank for development |
| **Application terms of service link** | Leave blank for development |

#### **Authorized Domains:**

Leave blank for localhost development.

#### **Developer Contact Information:**

| Field | Value |
|-------|-------|
| **Email addresses** | Your Gmail address |

Click **"Save and Continue"**

### 2.4 Scopes (Skip for Now)

1. On the "Scopes" page, click **"Save and Continue"**
2. Default scopes (email, profile, openid) are automatically included

### 2.5 Add Test Users

**IMPORTANT:** While your app is in "Testing" mode, only test users can sign in.

1. Click **"+ Add Users"**
2. Enter Gmail addresses (one per line):
   ```
   yourteam1@gmail.com
   yourteam2@gmail.com
   youremail@gmail.com
   ```
3. Click **"Add"**
4. Click **"Save and Continue"**

### 2.6 Review and Submit

1. Review your settings
2. Click **"Back to Dashboard"**

> **Note:** Your app will remain in "Testing" mode. This is fine for development. To go to production, you'll need to submit for Google verification (not required for this project).

---

## Step 3: Create OAuth Credentials

### 3.1 Navigate to Credentials

1. In the left sidebar: **APIs & Services** → **Credentials**
2. Or search for "Credentials" in the top search bar

### 3.2 Create OAuth Client ID

1. Click **"+ Create Credentials"** (top of page)
2. Select **"OAuth client ID"**

### 3.3 Configure OAuth Client

#### **Application Type:**
Select **"Web application"**

#### **Name:**
```
Localhost Development
```
Or any descriptive name (e.g., "Picnic Island Localhost")

#### **Authorized JavaScript Origins:**

Click **"+ Add URI"** and enter:
```
http://localhost
```

**For production/staging:**
```
https://yourdomain.com
```

#### **Authorized Redirect URIs:**

Click **"+ Add URI"** and enter:

**For localhost development:**
```
http://localhost/auth/google/callback
```

**For production/staging:**
```
https://yourdomain.com/auth/google/callback
```

> ⚠️ **IMPORTANT:** The redirect URI must match EXACTLY (no trailing slashes, correct protocol).

### 3.4 Create and Copy Credentials

1. Click **"Create"**
2. A popup will appear with your credentials:
   - **Client ID**: `239700245020-xxxxxxxxxxxxxxxx.apps.googleusercontent.com`
   - **Client Secret**: `GOCSPX-xxxxxxxxxxxxxxxx`
3. **Copy both values** (you'll need them in the next step)
4. Click **"OK"**

> 💡 **Tip:** You can always view these credentials later by clicking on the OAuth 2.0 Client ID name in the credentials list.

---

## Step 4: Configure Laravel Application

### 4.1 Add Credentials to `.env`

Open your `.env` file and add/update these lines:

```bash
# Google OAuth
GOOGLE_CLIENT_ID=your-client-id-here.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-your-client-secret-here
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
```

**Example:**
```bash
GOOGLE_CLIENT_ID=239700245020-hq91nb83qlp3hq3ouk7akktsb9h6the0.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=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
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
```

### 4.2 Clear Configuration Cache

```bash
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan config:cache
```

### 4.3 Test the Setup

1. Visit: `http://localhost/login`
2. Click **"Sign in with Google"**
3. You should be redirected to Google
4. Sign in with a test user account
5. Click "Continue" to authorize
6. You'll be redirected back and logged in!

---

## Adding Test Users

### Method 1: Via OAuth Consent Screen

1. Go to: **APIs & Services** → **OAuth consent screen**
2. Scroll down to **"Test users"** section
3. Click **"+ Add Users"**
4. Enter Gmail addresses (one per line)
5. Click **"Add"**
6. Click **"Save"** at the bottom of the page

### Method 2: Quick Add

1. Go to: **APIs & Services** → **OAuth consent screen**
2. Click **"Edit App"** button (top right)
3. Navigate through the steps until you reach **"Test users"**
4. Click **"+ Add Users"**
5. Enter emails
6. Click **"Save and Continue"** through the remaining steps

### Test User Requirements

- Must be a valid Gmail account (or Google Workspace account)
- Account must exist before adding
- No limit on number of test users
- Test users can be added/removed at any time

### Example Test Users

```
developer1@gmail.com
developer2@gmail.com
qa.tester@gmail.com
projectmanager@gmail.com
client.demo@gmail.com
```

---

## Managing Test Users

### View Current Test Users

1. **APIs & Services** → **OAuth consent screen**
2. Scroll to **"Test users"** section
3. View the list of authorized emails

### Remove Test Users

1. Go to **"Test users"** section
2. Click the **trash icon (🗑️)** next to the email you want to remove
3. Confirm deletion
4. Click **"Save"** at the bottom

### Update Test Users in Bulk

1. Click **"Edit App"**
2. Navigate to **"Test users"** step
3. Click **"Remove All"** to clear existing users
4. Add new users
5. Click **"Save and Continue"**

---

## Environment-Specific Setup

### Development (Localhost)

```bash
# .env
GOOGLE_CLIENT_ID=239700245020-xxxxxxxxx.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-xxxxxxxxx
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
```

**Google Cloud Console:**
- Authorized JavaScript origins: `http://localhost`
- Authorized redirect URIs: `http://localhost/auth/google/callback`

### Staging

```bash
# .env
GOOGLE_CLIENT_ID=different-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-different-secret
GOOGLE_REDIRECT_URI=https://staging.yourdomain.com/auth/google/callback
```

**Google Cloud Console:**
- Create a separate OAuth Client ID for staging
- Authorized JavaScript origins: `https://staging.yourdomain.com`
- Authorized redirect URIs: `https://staging.yourdomain.com/auth/google/callback`

### Production

```bash
# Environment Variables (NOT in .env file)
GOOGLE_CLIENT_ID=production-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-production-secret
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback
```

**Google Cloud Console:**
- Create a separate OAuth Client ID for production
- Authorized JavaScript origins: `https://yourdomain.com`
- Authorized redirect URIs: `https://yourdomain.com/auth/google/callback`
- **Submit app for verification** before going live (required for production)

---

## Troubleshooting

### Error: "400: invalid_request"

**Cause:** Redirect URI mismatch

**Solution:**
1. Check Google Cloud Console redirect URIs
2. Check `.env` file `GOOGLE_REDIRECT_URI`
3. Ensure they match **exactly** (no trailing slashes, correct protocol)
4. Run `sail artisan config:clear`

### Error: "401: invalid_client"

**Cause:** Wrong Client ID or Client Secret

**Solution:**
1. Go to Google Cloud Console → Credentials
2. Click on your OAuth 2.0 Client ID
3. Verify Client ID and Secret
4. Update `.env` file with correct values
5. Run `sail artisan config:clear`

### Error: "Access blocked: This app's request is invalid"

**Cause:** OAuth consent screen not configured

**Solution:**
1. Complete OAuth consent screen setup (Step 2)
2. Ensure test users are added
3. Ensure user signing in is in the test users list

### Error: "Sign in with Google button doesn't work"

**Cause:** Missing credentials or cached config

**Solution:**
```bash
# Check .env has credentials
grep GOOGLE_ .env

# Clear config cache
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan config:cache

# Restart Sail
./vendor/bin/sail restart
```

### User Not Authorized (Not in Test Users)

**Error Message:** "You're not authorized to use this app"

**Solution:**
1. Add the user's Gmail to test users (see "Adding Test Users" section)
2. Wait ~1 minute for changes to propagate
3. Try signing in again

---

## Security Best Practices

### ✅ DO

- ✅ Keep `.env` in `.gitignore`
- ✅ Use different OAuth clients for dev/staging/production
- ✅ Store credentials in environment variables in production
- ✅ Rotate secrets if compromised
- ✅ Limit test users to actual team members during development
- ✅ Use HTTPS in production

### ❌ DON'T

- ❌ Commit `.env` file to git
- ❌ Share Client Secret publicly
- ❌ Use same OAuth client for dev and production
- ❌ Hardcode credentials in code
- ❌ Use HTTP in production (must be HTTPS)

### Production Deployment Checklist

- [ ] Create separate Google Cloud project for production
- [ ] Create separate OAuth Client ID for production domain
- [ ] Use HTTPS (required for production)
- [ ] Store credentials in environment variables (not `.env` file)
- [ ] Submit app for Google verification (if publishing publicly)
- [ ] Remove "Testing" status and publish app
- [ ] Update authorized domains
- [ ] Test OAuth flow on production URL

---

## Additional Resources

### Official Documentation

- [Google OAuth 2.0 Documentation](https://developers.google.com/identity/protocols/oauth2)
- [Google Cloud Console](https://console.cloud.google.com/)
- [Laravel Socialite Documentation](https://laravel.com/docs/11.x/socialite)

### Support

- **Google Cloud Console Issues:** [Google Support](https://support.google.com/googleapi)
- **Laravel Socialite Issues:** [GitHub Issues](https://github.com/laravel/socialite/issues)

---

## Quick Reference

### Google Cloud Console URLs

| Page | URL |
|------|-----|
| **Console Home** | https://console.cloud.google.com/ |
| **OAuth Consent Screen** | https://console.cloud.google.com/apis/credentials/consent |
| **Credentials** | https://console.cloud.google.com/apis/credentials |
| **API Library** | https://console.cloud.google.com/apis/library |

### Laravel Commands

```bash
# Clear config cache
./vendor/bin/sail artisan config:clear

# Cache config
./vendor/bin/sail artisan config:cache

# View current config
./vendor/bin/sail artisan config:show services

# Restart Sail
./vendor/bin/sail restart
```

---

**Last Updated:** December 5, 2024
**Version:** 1.0.0
**Project:** Picnic Island Management System
