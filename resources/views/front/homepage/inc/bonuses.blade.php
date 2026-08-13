<section class="bc-home-section">
    <div class="container">
        <div class="bc-home-section__head">
            <div>
                <h2 class="bc-home-section__title">Trading Promotions &amp; Offers</h2>
                <p class="bc-home-section__sub">Exclusive bonuses, contests, and cashback deals</p>
            </div>
        </div>

        <div class="bc-home-promo-grid">
            <a href="{{ route('promotions.tab','deposit-bonuses') }}" class="bc-home-promo">
                <div class="bc-home-promo__top">
                    <div class="bc-home-promo__icon bc-home-promo__icon--blue">
                        <i class="fas fa-gift"></i>
                    </div>
                    <div>
                        <div class="bc-home-promo__title">Deposit Bonus</div>
                        <div class="bc-home-promo__desc">Up to 50% extra capital</div>
                    </div>
                </div>
                <div class="bc-home-promo__foot">
                    <span class="bc-home-promo__badge" style="background:#eff6ff;color:#2563eb;">Capital boost</span>
                    <span class="bc-home-promo__cta">Details →</span>
                </div>
            </a>

            <a href="{{ route('promotions.tab','no-deposit-bonuses') }}" class="bc-home-promo">
                <div class="bc-home-promo__top">
                    <div class="bc-home-promo__icon bc-home-promo__icon--green">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div>
                        <div class="bc-home-promo__title">No Deposit</div>
                        <div class="bc-home-promo__desc">Free trading credit</div>
                    </div>
                </div>
                <div class="bc-home-promo__foot">
                    <span class="bc-home-promo__badge" style="background:#f0fdf4;color:#16a34a;">Zero deposit</span>
                    <span class="bc-home-promo__cta">Details →</span>
                </div>
            </a>

            <a href="{{ route('promotions.tab','live-contests') }}" class="bc-home-promo">
                <div class="bc-home-promo__top">
                    <div class="bc-home-promo__icon bc-home-promo__icon--red">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div>
                        <div class="bc-home-promo__title">Live Contest</div>
                        <div class="bc-home-promo__desc">Real prize pools</div>
                    </div>
                </div>
                <div class="bc-home-promo__foot">
                    <span class="bc-home-promo__badge" style="background:#fef2f2;color:#dc2626;">Competition</span>
                    <span class="bc-home-promo__cta">Details →</span>
                </div>
            </a>

            <a href="{{ route('promotions.tab','demo-contests') }}" class="bc-home-promo">
                <div class="bc-home-promo__top">
                    <div class="bc-home-promo__icon bc-home-promo__icon--amber">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div>
                        <div class="bc-home-promo__title">Demo Contest</div>
                        <div class="bc-home-promo__desc">Practice with rewards</div>
                    </div>
                </div>
                <div class="bc-home-promo__foot">
                    <span class="bc-home-promo__badge" style="background:#fffbeb;color:#d97706;">Virtual funds</span>
                    <span class="bc-home-promo__cta">Details →</span>
                </div>
            </a>

            <a href="{{ route('promotions.tab','cashback-rebates') }}" class="bc-home-promo">
                <div class="bc-home-promo__top">
                    <div class="bc-home-promo__icon bc-home-promo__icon--purple">
                        <i class="fas fa-undo"></i>
                    </div>
                    <div>
                        <div class="bc-home-promo__title">Cashback Rebate</div>
                        <div class="bc-home-promo__desc">Up to 15% weekly</div>
                    </div>
                </div>
                <div class="bc-home-promo__foot">
                    <span class="bc-home-promo__badge" style="background:#faf5ff;color:#9333ea;">Loss recovery</span>
                    <span class="bc-home-promo__cta">Details →</span>
                </div>
            </a>

            <a href="{{ route('promotions.tab','crypto-bonuses') }}" class="bc-home-promo">
                <div class="bc-home-promo__top">
                    <div class="bc-home-promo__icon bc-home-promo__icon--orange">
                        <i class="fab fa-bitcoin"></i>
                    </div>
                    <div>
                        <div class="bc-home-promo__title">Crypto Bonus</div>
                        <div class="bc-home-promo__desc">+10% on crypto deposits</div>
                    </div>
                </div>
                <div class="bc-home-promo__foot">
                    <span class="bc-home-promo__badge bc-promo-badge--crypto">Digital assets</span>
                    <span class="bc-home-promo__cta">Details →</span>
                </div>
            </a>
        </div>
    </div>
</section>
