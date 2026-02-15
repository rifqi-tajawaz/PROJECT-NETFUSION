
-- PostgreSQL Schema for Supabase Migration

-- Users Table
CREATE TABLE IF NOT EXISTS public.users (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP(0) WITHOUT TIME ZONE,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE,
    -- Additional fields from migrations
    phone VARCHAR(255),
    address TEXT,
    login_alerts BOOLEAN DEFAULT false,
    avatar VARCHAR(255),
    two_factor_secret TEXT,
    two_factor_recovery_codes TEXT,
    two_factor_confirmed_at TIMESTAMP(0) WITHOUT TIME ZONE,
    provider VARCHAR(255),
    provider_id VARCHAR(255),
    provider_token VARCHAR(255),
    provider_refresh_token VARCHAR(255),
    is_active BOOLEAN DEFAULT true,
    is_locked BOOLEAN DEFAULT false,
    lock_reason VARCHAR(255),
    locked_at TIMESTAMP(0) WITHOUT TIME ZONE,
    password_changed_at TIMESTAMP(0) WITHOUT TIME ZONE,
    password_expires_at TIMESTAMP(0) WITHOUT TIME ZONE,
    must_change_password BOOLEAN DEFAULT false,
    last_login_at TIMESTAMP(0) WITHOUT TIME ZONE,
    last_login_ip VARCHAR(45),
    login_attempts INTEGER DEFAULT 0,
    membership_status VARCHAR(255) DEFAULT 'inactive', -- active, inactive, expired
    membership_package VARCHAR(255),
    membership_pay_date DATE,
    membership_expire DATE,
    email_notifications BOOLEAN DEFAULT true,
    current_session_id VARCHAR(255),
    trial_used_at TIMESTAMP(0) WITHOUT TIME ZONE,
    role VARCHAR(255) DEFAULT 'user'
);

-- Password Reset Tokens
CREATE TABLE IF NOT EXISTS public.password_reset_tokens (
    email VARCHAR(255) NOT NULL PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- Sessions (Database Driver)
CREATE TABLE IF NOT EXISTS public.sessions (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    user_id BIGINT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);
CREATE INDEX sessions_user_id_index ON public.sessions(user_id);
CREATE INDEX sessions_last_activity_index ON public.sessions(last_activity);

-- Cache Table
CREATE TABLE IF NOT EXISTS public.cache (
    key VARCHAR(255) NOT NULL PRIMARY KEY,
    value TEXT NOT NULL,
    expiration INTEGER NOT NULL
);

-- Jobs Table
CREATE TABLE IF NOT EXISTS public.jobs (
    id BIGSERIAL PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts SMALLINT NOT NULL,
    reserved_at INTEGER,
    available_at INTEGER NOT NULL,
    created_at INTEGER NOT NULL
);
CREATE INDEX jobs_queue_index ON public.jobs(queue);

-- Personal Access Tokens (Sanctum)
CREATE TABLE IF NOT EXISTS public.personal_access_tokens (
    id BIGSERIAL PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    abilities TEXT,
    last_used_at TIMESTAMP(0) WITHOUT TIME ZONE,
    expires_at TIMESTAMP(0) WITHOUT TIME ZONE,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);
CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens(tokenable_type, tokenable_id);

-- User Devices (2FA/Security)
CREATE TABLE IF NOT EXISTS public.user_devices (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
    device_name VARCHAR(255),
    ip_address VARCHAR(45),
    last_active_at TIMESTAMP(0) WITHOUT TIME ZONE,
    is_trusted BOOLEAN DEFAULT false,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- Activity Logs
CREATE TABLE IF NOT EXISTS public.activity_logs (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES public.users(id) ON DELETE SET NULL,
    action VARCHAR(255) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- Security Logs
CREATE TABLE IF NOT EXISTS public.security_logs (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT REFERENCES public.users(id) ON DELETE CASCADE,
    event VARCHAR(255) NOT NULL, -- login_success, login_failed, password_change, etc.
    ip_address VARCHAR(45),
    user_agent TEXT,
    details JSONB,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- Password History
CREATE TABLE IF NOT EXISTS public.password_histories (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- Email Verification Codes
CREATE TABLE IF NOT EXISTS public.email_verification_codes (
    id BIGSERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    code VARCHAR(6) NOT NULL,
    expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);
CREATE INDEX email_verification_codes_email_index ON public.email_verification_codes(email);

-- Hotspot Profiles
CREATE TABLE IF NOT EXISTS public.hotspot_profiles (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    rate_limit VARCHAR(255),
    session_timeout INTEGER,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- Hotspot Users
CREATE TABLE IF NOT EXISTS public.hotspot_users (
    id BIGSERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    profile_id BIGINT REFERENCES public.hotspot_profiles(id) ON DELETE SET NULL,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- Hotspot Vouchers
CREATE TABLE IF NOT EXISTS public.hotspot_vouchers (
    id BIGSERIAL PRIMARY KEY,
    code VARCHAR(255) NOT NULL UNIQUE,
    validity_days INTEGER NOT NULL,
    max_uses INTEGER DEFAULT 1,
    profile_id BIGINT NOT NULL REFERENCES public.hotspot_profiles(id) ON DELETE CASCADE,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- System Settings
CREATE TABLE IF NOT EXISTS public.system_settings (
    key VARCHAR(255) NOT NULL PRIMARY KEY,
    value TEXT,
    description TEXT,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- Support Tickets
CREATE TABLE IF NOT EXISTS public.tickets (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
    subject VARCHAR(255) NOT NULL,
    status VARCHAR(255) DEFAULT 'open', -- open, closed, pending
    priority VARCHAR(255) DEFAULT 'medium', -- low, medium, high
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- Ticket Replies
CREATE TABLE IF NOT EXISTS public.ticket_replies (
    id BIGSERIAL PRIMARY KEY,
    ticket_id BIGINT NOT NULL REFERENCES public.tickets(id) ON DELETE CASCADE,
    user_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
    message TEXT NOT NULL,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- FAQs
CREATE TABLE IF NOT EXISTS public.faqs (
    id BIGSERIAL PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    category VARCHAR(255),
    is_published BOOLEAN DEFAULT true,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- Documentation Categories
CREATE TABLE IF NOT EXISTS public.documentation_categories (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- Documentation Pages
CREATE TABLE IF NOT EXISTS public.documentation_pages (
    id BIGSERIAL PRIMARY KEY,
    category_id BIGINT REFERENCES public.documentation_categories(id) ON DELETE SET NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    is_published BOOLEAN DEFAULT true,
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- Transactions (Payment/Billing)
CREATE TABLE IF NOT EXISTS public.transactions (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
    external_id VARCHAR(255) NOT NULL UNIQUE, -- Xendit Invoice ID
    amount DECIMAL(15, 2) NOT NULL,
    status VARCHAR(255) DEFAULT 'PENDING', -- PENDING, PAID, EXPIRED, FAILED
    package_name VARCHAR(255) NOT NULL,
    duration VARCHAR(255) NOT NULL, -- monthly, yearly
    payment_channel VARCHAR(255),
    payment_method VARCHAR(255),
    paid_at TIMESTAMP(0) WITHOUT TIME ZONE,
    checkout_url VARCHAR(255),
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);

-- Trial Claims
CREATE TABLE IF NOT EXISTS public.trial_claims (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES public.users(id) ON DELETE CASCADE,
    ip_address VARCHAR(255) NOT NULL,
    browser_fingerprint VARCHAR(255),
    created_at TIMESTAMP(0) WITHOUT TIME ZONE,
    updated_at TIMESTAMP(0) WITHOUT TIME ZONE
);
CREATE INDEX trial_claims_ip_address_index ON public.trial_claims(ip_address);
CREATE INDEX trial_claims_browser_fingerprint_index ON public.trial_claims(browser_fingerprint);
