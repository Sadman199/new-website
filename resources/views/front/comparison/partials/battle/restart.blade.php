<section class="bc-battle-restart" aria-labelledby="bcBattleRestartTitle">
    <div class="bc-battle-restart__panel">
        <p class="bc-battle-restart__eyebrow">Start another battle</p>
        <h2 class="bc-battle-restart__title" id="bcBattleRestartTitle">Pick two brokers and fight again</h2>
        <p class="bc-battle-restart__sub">Choose two different brokers from the BrokersCourt database. Same-broker selections are blocked.</p>

        <form class="bc-battle-restart__form" id="bcBattleRestartForm" novalidate>
            <div class="bc-battle-restart__fields">
                <label class="bc-battle-restart__field">
                    <span>Select Broker 1</span>
                    <select id="bcBattleBroker1" name="broker1" required>
                        <option value="">Choose a broker</option>
                    </select>
                </label>
                <div class="bc-battle-restart__vs" aria-hidden="true">VS</div>
                <label class="bc-battle-restart__field">
                    <span>Select Broker 2</span>
                    <select id="bcBattleBroker2" name="broker2" required>
                        <option value="">Choose a broker</option>
                    </select>
                </label>
            </div>

            <p class="bc-battle-restart__error bc-compare-hidden" id="bcBattleRestartError" role="alert"></p>

            <button type="submit" class="bc-compare-btn bc-compare-btn--primary" id="bcBattleStartBtn">
                Start Battle
            </button>
        </form>
    </div>
</section>
