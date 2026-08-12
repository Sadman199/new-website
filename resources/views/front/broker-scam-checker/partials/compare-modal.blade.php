<div class="bsc-modal" id="bscCompareModal" hidden>
    <div class="bsc-modal__backdrop" data-bsc-close></div>
    <div class="bsc-modal__dialog bsc-modal__dialog--xl" role="dialog" aria-modal="true" aria-labelledby="bscCompareModalLabel">
        <div class="bsc-modal__content">
            <div class="bsc-modal__header">
                <h5 class="bsc-modal__title" id="bscCompareModalLabel">Compare broker safety</h5>
                <button type="button" class="bsc-modal__close" data-bsc-close aria-label="Close">&times;</button>
            </div>
            <div class="bsc-modal__body">
                <div class="bsc-compare-fields">
                    <label>
                        <span>Broker 1</span>
                        <input type="text" class="bsc-input bsc-compare-input" id="bscCompare1" placeholder="Current broker loaded">
                    </label>
                    <label>
                        <span>Broker 2</span>
                        <input type="text" class="bsc-input bsc-compare-input" id="bscCompare2" placeholder="Search broker">
                    </label>
                    <label>
                        <span>Broker 3 (optional)</span>
                        <input type="text" class="bsc-input bsc-compare-input" id="bscCompare3" placeholder="Search broker">
                    </label>
                </div>
                <button type="button" class="bsc-btn bsc-btn-primary" id="bscRunCompare">Run comparison</button>
                <div id="bscCompareResults" class="bsc-compare-results"></div>
            </div>
        </div>
    </div>
</div>
