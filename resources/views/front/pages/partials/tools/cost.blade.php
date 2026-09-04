<div class="td-grid td-grid-2">
    @if(($costBrokerHints ?? []) !== [])
        <div class="td-field td-field--full">
            <label for="cost-broker">Broker (optional)</label>
            <select id="cost-broker" data-field="broker_id">
                <option value="">Enter costs manually</option>
                @foreach($costBrokerHints as $hint)
                    <option value="{{ $hint['id'] }}">{{ $hint['name'] }}</option>
                @endforeach
            </select>
            <p class="calc-field-hint" data-broker-hint hidden></p>
        </div>
    @endif
    <div class="td-field">
        <label>Currency pair</label>
        <select data-field="pair">
            @foreach($pairs as $p)<option value="{{ $p }}">{{ $p }}</option>@endforeach
        </select>
    </div>
    <div class="td-field">
        <label>Lot size</label>
        <input type="number" data-field="lots" step="0.01" value="1" min="0.01">
    </div>
    <div class="td-field">
        <label>Spread (pips)</label>
        <input type="number" data-field="spread_pips" step="0.01" min="0" placeholder="Leave blank if unknown">
    </div>
    <div class="td-field">
        <label>Commission per lot</label>
        <input type="number" data-field="commission_per_lot" step="0.01" placeholder="Round-turn, if known">
    </div>
    <div class="td-field">
        <label>Swap per lot / night</label>
        <input type="number" data-field="swap_per_lot" step="0.01" placeholder="Leave blank if unknown">
    </div>
    <div class="td-field">
        <label>Trade duration (nights)</label>
        <input type="number" data-field="nights" step="1" value="0" min="0">
    </div>
    <div class="td-field">
        <label>Account currency</label>
        <select data-field="account_currency">
            @foreach($currencies as $c)<option value="{{ $c }}" {{ $c==='USD' ? 'selected' : '' }}>{{ $c }}</option>@endforeach
        </select>
    </div>
</div>
