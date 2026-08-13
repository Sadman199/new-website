@extends('front.layout.app')
@section('title', 'BrokersCourt Privacy Policy | Your Data Protection Rights')
@section('canonical', route('privacy'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/legal-page.css') }}?v=1">
@endpush

@section('main_content')
<div class="legal-page">
    <div class="legal-page__glow" aria-hidden="true"></div>
    <div class="container legal-page__inner">
        <header class="legal-page__head">
            <h1 class="legal-page__title">Brokers Court – Privacy Policy</h1>
            <p class="legal-page__meta">Effective Date: May 19, 2025</p>
        </header>

        <article class="legal-card mb-4">
            <div class="legal-card__body">
                <svg class="legal-card__icon legal-card__icon--amber" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path fill="none" d="M0 0h24v24H0z"></path>
                    <path d="M12 1 3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-1 6h2v2h-2V7zm0 4h2v6h-2v-6z"></path>
                </svg>
                <div>
                    <h2 class="legal-card__title legal-card__title--amber">Introduction</h2>
                    <p class="legal-card__text">At Brokers Court, we prioritize the privacy and security of our users' information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our forex broker comparison website. Please read this policy carefully.</p>
                </div>
            </div>
        </article>

        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6">
                <article class="legal-card">
                    <div class="legal-card__body">
                        <svg class="legal-card__icon legal-card__icon--blue" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M622.3 271.1l-115.2-45c-4.1-1.6-12.6-3.7-22.2 0l-115.2 45c-10.7 4.2-17.7 14-17.7 24.9 0 111.6 68.7 188.8 132.9 213.9 9.6 3.7 18 1.6 22.2 0C558.4 489.9 640 420.5 640 296c0-10.9-7-20.7-17.7-24.9zM496 462.4V273.3l95.5 37.3c-5.6 87.1-60.9 135.4-95.5 151.8zM224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm96 40c0-2.5.8-4.8 1.1-7.2-2.5-.1-4.9-.8-7.5-.8h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c6.8 0 13.3-1.5 19.2-4-54-42.9-99.2-116.7-99.2-212z"></path></svg>
                        <div>
                            <h2 class="legal-card__title legal-card__title--blue">1. Information We Collect</h2>
                            <p class="legal-card__text">We collect several types of information from and about users of our website, including:</p>
                            <ul class="legal-card__list">
                                <li><strong>Personal Data:</strong> Email address, name, and other contact details when you register or contact us</li>
                                <li><strong>Usage Data:</strong> Information about how you interact with our broker comparisons and reviews</li>
                                <li><strong>Technical Data:</strong> IP address, browser type, operating system, and other diagnostic data</li>
                                <li><strong>Cookies:</strong> We use cookies to enhance your experience (see Section 4)</li>
                            </ul>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-12 col-md-6">
                <article class="legal-card">
                    <div class="legal-card__body">
                        <svg class="legal-card__icon legal-card__icon--purple" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M0 168v-16c0-13.255 10.745-24 24-24h360V80c0-21.367 25.899-32.042 40.971-16.971l80 80c9.372 9.373 9.372 24.569 0 33.941l-80 80C409.956 271.982 384 261.456 384 240v-48H24c-13.255 0-24-10.745-24-24zm488 152H128v-48c0-21.314-25.862-32.08-40.971-16.971l-80 80c-9.372 9.373-9.372 24.569 0 33.941l80 80C102.057 463.997 128 453.437 128 432v-48h360c13.255 0 24-10.745 24-24v-16c0-13.255-10.745-24-24-24z"></path></svg>
                        <div>
                            <h2 class="legal-card__title legal-card__title--purple">2. How We Use Your Information</h2>
                            <p class="legal-card__text">We use the information we collect for various purposes in our forex broker comparison services:</p>
                            <ul class="legal-card__list">
                                <li>To provide and maintain our broker review services</li>
                                <li>To notify you about changes to our service</li>
                                <li>To allow you to participate in interactive features</li>
                                <li>To provide customer support</li>
                                <li>To gather analysis for improving our site</li>
                                <li>To monitor usage patterns and technical performance</li>
                                <li>To detect and prevent fraud in financial broker reviews</li>
                            </ul>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-12 col-md-6">
                <article class="legal-card">
                    <div class="legal-card__body">
                        <svg class="legal-card__icon legal-card__icon--cyan" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M20 17V7c0-2.168-3.663-4-8-4S4 4.832 4 7v10c0 2.168 3.663 4 8 4s8-1.832 8-4zM12 5c3.691 0 5.931 1.507 6 1.994C17.931 7.493 15.691 9 12 9S6.069 7.493 6 7.006C6.069 6.507 8.309 5 12 5zM6 9.607C7.479 10.454 9.637 11 12 11s4.521-.546 6-1.393v2.387c-.069.499-2.309 2.006-6 2.006s-5.931-1.507-6-2V9.607zM6 17v-2.393C7.479 15.454 9.637 16 12 16s4.521-.546 6-1.393v2.387c-.069.499-2.309 2.006-6 2.006s-5.931-1.507-6-2z"></path></svg>
                        <div>
                            <h2 class="legal-card__title legal-card__title--cyan">3. Data Sharing &amp; Disclosure</h2>
                            <p class="legal-card__text">We may share your information in the following situations:</p>
                            <ul class="legal-card__list">
                                <li><strong>Service Providers:</strong> With third parties who perform services for us (analytics, hosting, etc.)</li>
                                <li><strong>Business Transfers:</strong> In connection with any merger or sale of company assets</li>
                                <li><strong>Legal Requirements:</strong> When required by law or to protect rights and safety</li>
                                <li><strong>Affiliates:</strong> With our affiliates in the financial services sector</li>
                            </ul>
                            <p class="legal-card__text">We <strong>do not</strong> sell your personal information to forex brokers or trading platforms.</p>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-12 col-md-6">
                <article class="legal-card">
                    <div class="legal-card__body">
                        <svg class="legal-card__icon legal-card__icon--red" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="none" d="M0 0h24v24H0z"></path><path d="M12 1 3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"></path></svg>
                        <div>
                            <h2 class="legal-card__title legal-card__title--red">4. Data Security</h2>
                            <p class="legal-card__text">We implement security measures designed to protect your information in our financial review platform:</p>
                            <ul class="legal-card__list">
                                <li>SSL encryption for all data transmissions</li>
                                <li>Regular security audits of our systems</li>
                                <li>Access controls to personal information</li>
                                <li>Secure server infrastructure with firewalls</li>
                            </ul>
                            <p class="legal-card__text">However, no internet transmission is completely secure, and we cannot guarantee absolute security of data shared with us.</p>
                        </div>
                    </div>
                </article>
            </div>
        </div>

        <article class="legal-card mb-4">
            <div class="legal-card__body">
                <svg class="legal-card__icon legal-card__icon--emerald" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M510.37 254.79l-12.08-76.26a132.493 132.493 0 0 0-37.16-72.95l-54.76-54.75c-19.73-19.72-45.18-32.7-72.71-37.05l-76.7-12.15c-27.51-4.36-55.69.11-80.52 12.76L107.32 49.6a132.25 132.25 0 0 0-57.79 57.8l-35.1 68.88a132.602 132.602 0 0 0-12.82 80.94l12.08 76.27a132.493 132.493 0 0 0 37.16 72.95l54.76 54.75a132.087 132.087 0 0 0 72.71 37.05l76.7 12.14c27.51 4.36 55.69-.11 80.52-12.75l69.12-35.21a132.302 132.302 0 0 0 57.79-57.8l35.1-68.87c12.71-24.96 17.2-53.3 12.82-80.96zM176 368c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32zm32-160c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32zm160 128c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32z"></path></svg>
                <div class="flex-grow-1">
                    <h2 class="legal-card__title legal-card__title--emerald">5. Cookies &amp; Tracking Technologies</h2>
                    <p class="legal-card__text">We use cookies and similar tracking technologies to track activity on our forex comparison website:</p>
                    <div class="row g-3">
                        <div class="col-12 col-md-4"><div class="legal-subcard"><h3 class="legal-subcard__title legal-subcard__title--emerald">Essential Cookies</h3><p class="legal-subcard__text">Necessary for the website to function and cannot be switched off</p></div></div>
                        <div class="col-12 col-md-4"><div class="legal-subcard"><h3 class="legal-subcard__title legal-subcard__title--emerald">Analytics Cookies</h3><p class="legal-subcard__text">Help us understand how visitors interact with our broker reviews</p></div></div>
                        <div class="col-12 col-md-4"><div class="legal-subcard"><h3 class="legal-subcard__title legal-subcard__title--emerald">Preference Cookies</h3><p class="legal-subcard__text">Remember your display preferences for our financial charts</p></div></div>
                        <div class="col-12 col-md-4"><div class="legal-subcard"><h3 class="legal-subcard__title legal-subcard__title--emerald">Marketing Cookies</h3><p class="legal-subcard__text">Used to track effectiveness of our financial content campaigns</p></div></div>
                        <div class="col-12 col-md-4"><div class="legal-subcard"><h3 class="legal-subcard__title legal-subcard__title--emerald">Functional Cookies</h3><p class="legal-subcard__text">Enable the website to provide enhanced functionality and personalization</p></div></div>
                        <div class="col-12 col-md-4"><div class="legal-subcard"><h3 class="legal-subcard__title legal-subcard__title--emerald">Targeting Cookies</h3><p class="legal-subcard__text">Used to deliver relevant advertising based on your interests</p></div></div>
                    </div>
                    <p class="legal-card__text mt-3 mb-0">You can instruct your browser to refuse all cookies, but some parts of our forex comparison tools may not function properly.</p>
                </div>
            </div>
        </article>

        <article class="legal-card mb-4">
            <div class="legal-card__body">
                <svg class="legal-card__icon legal-card__icon--yellow" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 496 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M336.5 160C322 70.7 287.8 8 248 8s-74 62.7-88.5 152h177zM152 256c0 22.2 1.2 43.5 3.3 64h185.3c2.1-20.5 3.3-41.8 3.3-64s-1.2-43.5-3.3-64H155.3c-2.1 20.5-3.3 41.8-3.3 64zm324.7-96c-28.6-67.9-86.5-120.4-158-141.6 24.4 33.8 41.2 84.7 50 141.6h108zM177.2 18.4C105.8 39.6 47.8 92.1 19.3 160h108c8.7-56.9 25.5-107.8 49.9-141.6zM487.4 192H372.7c2.1 21 3.3 42.5 3.3 64s-1.2 43-3.3 64h114.6c5.5-20.5 8.6-41.8 8.6-64s-3.1-43.5-8.5-64zM120 256c0-21.5 1.2-43 3.3-64H8.6C3.2 212.5 0 233.8 0 256s3.2 43.5 8.6 64h114.6c-2-21-3.2-42.5-3.2-64zm39.5 96c14.5 89.3 48.7 152 88.5 152s74-62.7 88.5-152h-177zm159.3 141.6c71.4-21.2 129.4-73.7 158-141.6h-108c-8.8 56.9-25.6 107.8-50 141.6zM19.3 352c28.6 67.9 86.5 120.4 158 141.6-24.4-33.8-41.2-84.7-50-141.6h-108z"></path></svg>
                <div>
                    <h2 class="legal-card__title legal-card__title--yellow">6. International Data Transfers</h2>
                    <p class="legal-card__text">Your information, including personal data, may be transferred to — and maintained on — computers located outside of your country where data protection laws may differ. For forex traders in the European Economic Area (EEA), we ensure transfers comply with GDPR requirements through standard contractual clauses.</p>
                </div>
            </div>
        </article>

        <article class="legal-card mb-4">
            <div class="legal-card__body">
                <svg class="legal-card__icon legal-card__icon--green" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="none" d="M0 0h24v24H0z"></path><path d="m21 5-9-4-9 4v6c0 5.55 3.84 10.74 9 12 2.3-.56 4.33-1.9 5.88-3.71l-3.12-3.12a4.994 4.994 0 0 1-6.29-.64 5.003 5.003 0 0 1 0-7.07 5.003 5.003 0 0 1 7.07 0 5.006 5.006 0 0 1 .64 6.29l2.9 2.9C20.29 15.69 21 13.38 21 11V5z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                <div class="flex-grow-1">
                    <h2 class="legal-card__title legal-card__title--green">7. Your Data Protection Rights</h2>
                    <p class="legal-card__text">Depending on your location, you may have rights under data protection laws, including:</p>
                    <div class="row g-3">
                        <div class="col-12 col-md-4"><div class="legal-subcard"><h3 class="legal-subcard__title legal-subcard__title--green">Access &amp; Portability</h3><p class="legal-subcard__text">Request copies or transfer of your personal data to another provider in a structured, commonly used, and machine-readable format.</p></div></div>
                        <div class="col-12 col-md-4"><div class="legal-subcard"><h3 class="legal-subcard__title legal-subcard__title--green">Rectification</h3><p class="legal-subcard__text">Request correction of inaccurate or incomplete personal data we hold about you, ensuring it is up to date.</p></div></div>
                        <div class="col-12 col-md-4"><div class="legal-subcard"><h3 class="legal-subcard__title legal-subcard__title--green">Erasure</h3><p class="legal-subcard__text">Request deletion of your personal data, especially if it is no longer necessary for the purposes for which it was collected.</p></div></div>
                        <div class="col-12 col-md-4"><div class="legal-subcard"><h3 class="legal-subcard__title legal-subcard__title--green">Objection</h3><p class="legal-subcard__text">Object to our processing of your data on the basis of legitimate interests or for direct marketing purposes.</p></div></div>
                        <div class="col-12 col-md-4"><div class="legal-subcard"><h3 class="legal-subcard__title legal-subcard__title--green">Restriction</h3><p class="legal-subcard__text">Request limitation on the processing of your personal data, including in situations where you contest the accuracy of your data.</p></div></div>
                        <div class="col-12 col-md-4"><div class="legal-subcard"><h3 class="legal-subcard__title legal-subcard__title--green">Automated Decisions</h3><p class="legal-subcard__text">Request to opt out of automated decision-making processes that significantly affect you, including profiling and scoring activities.</p></div></div>
                    </div>
                </div>
            </div>
        </article>

        <article class="legal-card legal-card--footer">
            <h2 class="legal-card__title legal-card__title--amber">Contact Us &amp; Policy Updates</h2>
            <p class="legal-card__text">We periodically update our Privacy Policy to ensure that our practices align with legal requirements and our commitment to protecting your data. We will notify you of any changes by posting the updated policy on this page.</p>
            <p class="legal-card__text">If you have any questions regarding this policy or how we handle your data, please contact us at:<br>
                <a href="mailto:privacy@brokerscourt.com" class="legal-link">privacy@brokerscourt.com</a>
            </p>
        </article>
    </div>
</div>
@endsection
