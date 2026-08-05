<div class="td-grid td-grid-2">
    <div class="td-field">
        <label>Account Balance</label>
        <input type="number" data-field="balance" step="0.01" value="10000">
    </div>
    <div class="td-field">
        <label>Risk %</label>
        <input type="number" data-field="risk_percent" step="0.1" value="1">
    </div>
    <div class="td-field">
        <label>Stop Loss (pips)</label>
        <input type="number" data-field="sl_pips" step="0.1" value="20">
    </div>
    <div class="td-field">
        <label>Currency Pair</label>
        <select data-field="pair">
            @foreach($pairs as $p)<option value="{{ $p }}">{{ $p }}</option>@endforeach
        </select>
    </div>
    <div class="td-field">
        <label>Account Currency</label>
        <select data-field="account_currency">
            @foreach($currencies as $c)<option value="{{ $c }}" {{ $c==='USD' ? "selected" : "" }}>{{ $c }}</option>@endforeach
        </select>
    </div>
    <div class="td-field">
        <label>Price (optional)</label>
        <input type="number" data-field="price" step="0.00001" placeholder="Auto if empty">
    </div>
</div>
