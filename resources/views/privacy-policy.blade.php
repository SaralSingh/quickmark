@extends('layouts.guest')

@section('title', 'QuickMark | Privacy Policy')

@section('meta_description', 'Privacy Policy for QuickMark. Learn how we handle your presence data and API usage.')

@push('head')
<style>
    .policy-header {
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        padding: 60px 0;
        border-bottom: 1px solid var(--bs-card-border);
    }
    .policy-content h4 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 700;
        color: var(--bs-primary);
    }
    .policy-content p, .policy-content li {
        color: var(--bs-secondary);
        line-height: 1.7;
    }
</style>
@endpush

@section('content')
<section class="policy-header text-center">
    <div class="container">
        <h1 class="display-5 fw-bold text-dark mb-3">Privacy Policy</h1>
        <p class="lead text-secondary">Effective Date: February 2026</p>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 policy-content">
                <p>
                    Welcome to QuickMark ("we", "our", or "us"). This Privacy Policy explains how we collect, use, and protect your information when you use our API-first presence marking system (the "Service") hosted at <a href="https://qmark.alwaysdata.net">qmark.alwaysdata.net</a>.
                </p>

                <h4>1. Information We Collect</h4>
                <p>We collect minimal information necessary to provide the Service:</p>
                <ul>
                    <li><strong>Account Data:</strong> When you register, we collect your name, email address, and a securely hashed password.</li>
                    <li><strong>List & Session Data:</strong> As a user, you may create lists containing names of individuals. You are responsible for the names you enter into the system.</li>
                    <li><strong>Presence Data:</strong> We store binary attendance records (Present/Absent) linked to the sessions you create.</li>
                </ul>

                <h4>2. How We Use Your Information</h4>
                <p>We use the collected information solely to:</p>
                <ul>
                    <li>Authenticate your access to the QuickMark dashboard and API endpoints.</li>
                    <li>Store and retrieve the lists, sessions, and attendance records you actively create.</li>
                    <li>Maintain the security and functional integrity of the Service.</li>
                </ul>

                <h4>3. Data Sharing and Disclosure</h4>
                <p>
                    <strong>We do not sell, rent, or trade your personal data.</strong> QuickMark is built as an independent tool by Saral Singh. We only share information if legally required to do so by applicable law.
                </p>

                <h4>4. API Usage</h4>
                <p>
                    QuickMark is an API-first platform. All data transmitted between the frontend applications and the QuickMark database occurs via standardized API endpoints. You are responsible for securing any API access credentials associated with your account.
                </p>

                <h4>5. Data Retention & Deletion</h4>
                <p>
                    We retain your list and session data as long as your account is active. If you wish to permanently delete your account and all associated attendance records, you may contact the developer directly.
                </p>

                <h4>6. Changes to This Policy</h4>
                <p>
                    We may update this Privacy Policy periodically. We will reflect any changes by updating the "Effective Date" at the top of this page.
                </p>

                <h4>7. Contact Us</h4>
                <p>
                    If you have any questions or concerns about this Privacy Policy or how your data is handled, please contact the developer at:
                    <br><a href="mailto:saralsingh2005@gmail.com">saralsingh2005@gmail.com</a>
                </p>

            </div>
        </div>
    </div>
</section>
@endsection
