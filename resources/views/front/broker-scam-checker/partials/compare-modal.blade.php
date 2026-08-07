<div class="modal fade bsc-modal" id="bscCompareModal" tabindex="-1" aria-labelledby="bscCompareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bscCompareModalLabel">Compare broker safety</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Broker 1</label>
                        <input type="text" class="form-control bsc-input bsc-compare-input" id="bscCompare1" placeholder="Current broker loaded">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Broker 2</label>
                        <input type="text" class="form-control bsc-input bsc-compare-input" id="bscCompare2" placeholder="Search broker">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Broker 3 (optional)</label>
                        <input type="text" class="form-control bsc-input bsc-compare-input" id="bscCompare3" placeholder="Search broker">
                    </div>
                </div>
                <button type="button" class="btn bsc-btn-primary mb-3" id="bscRunCompare">Run comparison</button>
                <div id="bscCompareResults" class="bsc-compare-results"></div>
            </div>
        </div>
    </div>
</div>
