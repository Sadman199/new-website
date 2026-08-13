@extends('front.layout.app')
@section('title', 'Disclaimer | BrokersCourt')
@section('canonical', route('disclaimer'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/legal-page.css') }}?v=1">
@endpush

@section('main_content')
<div class="legal-page">
    <div class="legal-page__glow" aria-hidden="true"></div>
    <div class="container legal-page__inner">
        <header class="legal-page__head">
            <h1 class="legal-page__title">Brokers Court – Disclaimer</h1>
            <p class="legal-page__meta">Important Legal Notice Regarding Forex Broker Information</p>
        </header>

        <article class="legal-card legal-card--alert mb-4">
            <div class="legal-card__body">
                <svg class="legal-card__icon legal-card__icon--red" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="none" d="M0 0h24v24H0z"></path><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"></path></svg>
                <div>
                    <h2 class="legal-card__title legal-card__title--red">General Disclaimer</h2>
                    <p class="legal-card__text">The information provided on Brokers Court is for general informational purposes only. All broker reviews, comparisons, and content on this website are published in good faith, however we make no representation or warranty of any kind, express or implied, regarding the accuracy, adequacy, validity, reliability, availability, or completeness of any information on the site.</p>
                </div>
            </div>
        </article>

        <div class="row g-4">
            <div class="col-12">
                <article class="legal-card">
                    <div class="legal-card__body">
                        <svg class="legal-card__icon legal-card__icon--amber" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M621.16 54.46C582.37 38.19 543.55 32 504.75 32c-123.17-.01-246.33 62.34-369.5 62.34-30.89 0-61.76-3.92-92.65-13.72-3.47-1.1-6.95-1.62-10.35-1.62C15.04 79 0 92.32 0 110.81v317.26c0 12.63 7.23 24.6 18.84 29.46C57.63 473.81 96.45 480 135.25 480c123.17 0 246.34-62.35 369.51-62.35 30.89 0 61.76 3.92 92.65 13.72 3.47 1.1 6.95 1.62 10.35 1.62 17.21 0 32.25-13.32 32.25-31.81V83.93c-.01-12.64-7.24-24.6-18.85-29.47zM48 132.22c20.12 5.04 41.12 7.57 62.72 8.93C104.84 170.54 79 192.69 48 192.69v-60.47zm0 285v-47.78c34.37 0 62.18 27.27 63.71 61.4-22.53-1.81-43.59-6.31-63.71-13.62zM320 352c-44.19 0-80-42.99-80-96 0-53.02 35.82-96 80-96s80 42.98 80 96c0 53.03-35.83 96-80 96zm272 27.78c-17.52-4.39-35.71-6.85-54.32-8.44 5.87-26.08 27.5-45.88 54.32-49.28v57.72zm0-236.11c-30.89-3.91-54.86-29.7-55.81-61.55 19.54 2.17 38.09 6.23 55.81 12.66v48.89z"></path></svg>
                        <div class="flex-grow-1">
                            <h2 class="legal-card__title legal-card__title--amber">1. Forex Trading Risk Warning</h2>
                            <p class="legal-card__text">Trading foreign exchange (Forex) or contracts for differences (CFDs) on margin carries a high level of risk and may not be suitable for all investors. The possibility exists that you could sustain a loss of some or all of your initial investment and therefore you should not invest money that you cannot afford to lose.</p>
                            <div class="legal-subcard legal-subcard--quote">
                                <p class="legal-subcard__text legal-subcard__text--italic">"Past performance is not indicative of future results. The high degree of leverage available in Forex trading can work against you as well as for you."</p>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-12">
                <article class="legal-card">
                    <div class="legal-card__body">
                        <svg class="legal-card__icon legal-card__icon--blue" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 352 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M176 80c-52.94 0-96 43.06-96 96 0 8.84 7.16 16 16 16s16-7.16 16-16c0-35.3 28.72-64 64-64 8.84 0 16-7.16 16-16s-7.16-16-16-16zM96.06 459.17c0 3.15.93 6.22 2.68 8.84l24.51 36.84c2.97 4.46 7.97 7.14 13.32 7.14h78.85c5.36 0 10.36-2.68 13.32-7.14l24.51-36.84c1.74-2.62 2.67-5.7 2.68-8.84l.05-43.18H96.02l.04 43.18zM176 0C73.72 0 0 82.97 0 176c0 44.37 16.45 84.85 43.56 115.78 16.64 18.99 42.74 58.8 52.42 92.16v.06h48v-.12c-.01-4.77-.72-9.51-2.15-14.07-5.59-17.81-22.82-64.77-62.17-109.67-20.54-23.43-31.52-53.15-31.61-84.14-.2-73.64 59.67-128 127.95-128 70.58 0 128 57.42 128 128 0 30.97-11.24 60.85-31.65 84.14-39.11 44.61-56.42 91.47-62.1 109.46a47.507 47.507 0 0 0-2.22 14.3v.1h48v-.05c9.68-33.37 35.78-73.18 52.42-92.16C335.55 260.85 352 220.37 352 176 352 78.8 273.2 0 176 0z"></path></svg>
                        <div>
                            <h2 class="legal-card__title legal-card__title--blue">2. No Investment Advice</h2>
                            <p class="legal-card__text">The content on Brokers Court does not constitute investment advice, financial advice, trading advice, or any other sort of advice and you should not treat any of the website's content as such. Brokers Court does not recommend that any particular financial instrument, trading strategy, or broker should be bought, sold, or held by you.</p>
                            <ul class="legal-card__list">
                                <li>We are not financial advisors</li>
                                <li>We do not provide personalized recommendations</li>
                                <li>All trading decisions are your sole responsibility</li>
                                <li>Always conduct your own due diligence before choosing a broker</li>
                            </ul>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-12">
                <article class="legal-card">
                    <div class="legal-card__body">
                        <svg class="legal-card__icon legal-card__icon--emerald" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M496 384H64V80c0-8.84-7.16-16-16-16H16C7.16 64 0 71.16 0 80v336c0 17.67 14.33 32 32 32h464c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16zM464 96H345.94c-21.38 0-32.09 25.85-16.97 40.97l32.4 32.4L288 242.75l-73.37-73.37c-12.5-12.5-32.76-12.5-45.25 0l-68.69 68.69c-6.25 6.25-6.25 16.38 0 22.63l22.62 22.62c6.25 6.25 16.38 6.25 22.63 0L192 237.25l73.37 73.37c12.5 12.5 32.76 12.5 45.25 0l96-96 32.4 32.4c15.12 15.12 40.97 4.41 40.97-16.97V112c.01-8.84-7.15-16-15.99-16z"></path></svg>
                        <div class="flex-grow-1">
                            <h2 class="legal-card__title legal-card__title--emerald">3. Broker Reviews &amp; Accuracy</h2>
                            <p class="legal-card__text">Our broker reviews and comparisons are based on:</p>
                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6"><div class="legal-subcard"><h3 class="legal-subcard__title legal-subcard__title--emerald">Public Information</h3><p class="legal-subcard__text">Regulatory filings, broker websites, and public disclosures</p></div></div>
                                <div class="col-12 col-md-6"><div class="legal-subcard"><h3 class="legal-subcard__title legal-subcard__title--emerald">User Feedback</h3><p class="legal-subcard__text">Aggregated user reviews and experiences</p></div></div>
                                <div class="col-12 col-md-6"><div class="legal-subcard"><h3 class="legal-subcard__title legal-subcard__title--emerald">Our Analysis</h3><p class="legal-subcard__text">Independent testing and evaluation</p></div></div>
                                <div class="col-12 col-md-6"><div class="legal-subcard"><h3 class="legal-subcard__title legal-subcard__title--emerald">Third-Party Data</h3><p class="legal-subcard__text">From reliable financial data providers</p></div></div>
                            </div>
                            <p class="legal-card__text">Broker conditions change frequently. While we strive to keep information current, we cannot guarantee all details are up-to-date at all times.</p>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-12">
                <article class="legal-card">
                    <div class="legal-card__body">
                        <svg class="legal-card__icon legal-card__icon--purple" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="none" d="M0 0h24v24H0z"></path><path d="M12 1 3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm7 10c0 1.85-.51 3.65-1.38 5.21l-1.45-1.45a4.994 4.994 0 0 0-.64-6.29 5.003 5.003 0 0 0-7.07 0 5.003 5.003 0 0 0 0 7.07 5.006 5.006 0 0 0 6.29.64l1.72 1.72c-1.19 1.42-2.73 2.51-4.47 3.04-4.02-1.25-7-5.42-7-9.94V6.3l7-3.11 7 3.11V11zm-7 4c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z"></path></svg>
                        <div class="flex-grow-1">
                            <h2 class="legal-card__title legal-card__title--purple">4. Affiliate Relationships</h2>
                            <p class="legal-card__text">Brokers Court may have financial relationships with some of the brokers mentioned on this website. We may receive compensation if you click on links to broker sites or open accounts with brokers through our site.</p>
                            <div class="legal-subcard">
                                <h3 class="legal-subcard__title legal-subcard__title--purple">Our Promise Regarding Affiliate Links</h3>
                                <ul class="legal-card__list">
                                    <li>Affiliate relationships do not influence our reviews or rankings</li>
                                    <li>We maintain strict separation between editorial and commercial content</li>
                                    <li>Higher commissions do not result in better ratings</li>
                                    <li>We disclose all affiliate relationships transparently</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-12">
                <article class="legal-card">
                    <div class="legal-card__body">
                        <svg class="legal-card__icon legal-card__icon--cyan" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 640 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M256 336h-.02c0-16.18 1.34-8.73-85.05-181.51-17.65-35.29-68.19-35.36-85.87 0C-2.06 328.75.02 320.33.02 336H0c0 44.18 57.31 80 128 80s128-35.82 128-80zM128 176l72 144H56l72-144zm511.98 160c0-16.18 1.34-8.73-85.05-181.51-17.65-35.29-68.19-35.36-85.87 0-87.12 174.26-85.04 165.84-85.04 181.51H384c0 44.18 57.31 80 128 80s128-35.82 128-80h-.02zM440 320l72-144 72 144H440zm88 128H352V153.25c23.51-10.29 41.16-31.48 46.39-57.25H528c8.84 0 16-7.16 16-16V48c0-8.84-7.16-16-16-16H383.64C369.04 12.68 346.09 0 320 0s-49.04 12.68-63.64 32H112c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h129.61c5.23 25.76 22.87 46.96 46.39 57.25V448H112c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h416c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z"></path></svg>
                        <div>
                            <h2 class="legal-card__title legal-card__title--cyan">5. Third-Party Links &amp; Content</h2>
                            <p class="legal-card__text">Our website may contain links to third-party websites or content. We:</p>
                            <ul class="legal-card__list">
                                <li>Do not control these external sites</li>
                                <li>Are not responsible for their content</li>
                                <li>Do not endorse their views or guarantee their accuracy</li>
                                <li>Provide these links for informational purposes only</li>
                            </ul>
                            <p class="legal-card__text">You access third-party links at your own risk. We strongly advise you review the terms and privacy policies of any third-party sites you visit.</p>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-12">
                <article class="legal-card">
                    <div class="legal-card__body">
                        <svg class="legal-card__icon legal-card__icon--yellow" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="none" d="M0 0h24v24H0z"></path><path d="m5.25 8.069 2.83-2.827 14.134 14.15-2.83 2.827zM9.486 3.827 12.314.998l5.657 5.656-2.828 2.83zM.999 12.315l2.828-2.829 5.657 5.657-2.828 2.828zM1 21h12v2H1z"></path></svg>
                        <div class="flex-grow-1">
                            <h2 class="legal-card__title legal-card__title--yellow">6. Jurisdiction &amp; Regulatory Notice</h2>
                            <p class="legal-card__text">Brokers Court operates as an informational website only. We are not a brokerage firm, financial advisor, or regulated entity.</p>
                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6"><div class="legal-subcard"><h3 class="legal-subcard__title legal-subcard__title--yellow">Regional Restrictions</h3><p class="legal-subcard__text">Some brokers may not be available in your jurisdiction due to local regulations</p></div></div>
                                <div class="col-12 col-md-6"><div class="legal-subcard"><h3 class="legal-subcard__title legal-subcard__title--yellow">Your Responsibility</h3><p class="legal-subcard__text">Ensure any broker you choose is properly licensed in your country</p></div></div>
                            </div>
                            <p class="legal-card__text">Forex trading may be restricted in some countries. It is your responsibility to comply with local laws.</p>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-12">
                <article class="legal-card">
                    <div class="legal-card__body">
                        <svg class="legal-card__icon legal-card__icon--red" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 576 512" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M569.517 440.013C587.975 472.007 564.806 512 527.94 512H48.054c-36.937 0-59.999-40.055-41.577-71.987L246.423 23.985c18.467-32.009 64.72-31.951 83.154 0l239.94 416.028zM288 354c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"></path></svg>
                        <div>
                            <h2 class="legal-card__title legal-card__title--red">7. Limitation of Liability</h2>
                            <p class="legal-card__text">Under no circumstance shall Brokers Court, its directors, employees, partners, or affiliates be liable for:</p>
                            <ul class="legal-card__list">
                                <li>Any loss or damage resulting from reliance on our content</li>
                                <li>Any trading losses or investment decisions made based on our information</li>
                                <li>Any errors or omissions in our broker reviews</li>
                                <li>Any interruption or cessation of service</li>
                                <li>Any bugs, viruses, or harmful components on the site</li>
                            </ul>
                            <p class="legal-card__text">Your use of Brokers Court and any reliance on its content is solely at your own risk.</p>
                        </div>
                    </div>
                </article>
            </div>
        </div>

        <article class="legal-card legal-card--alert legal-card--footer">
            <h2 class="legal-card__title legal-card__title--red">Important Final Notice</h2>
            <p class="legal-card__text">By using Brokers Court, you acknowledge that you have read, understood, and agree to be bound by this Disclaimer. If you do not agree with any part of this disclaimer, you must not use our website.</p>
            <p class="legal-card__text">For questions about this disclaimer:<br>
                <a href="mailto:legal@brokerscourt.com" class="legal-link legal-link--red">legal@brokerscourt.com</a>
            </p>
        </article>
    </div>
</div>
@endsection
