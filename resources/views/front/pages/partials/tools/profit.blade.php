<div class="td-grid td-grid-2">
    <div class="td-field">
        <label>Currency Pair</label>
        <select data-field="pair">
            @foreach($pairs as $p)<option value="{{ $p }}">{{ $p }}</option>@endforeach
        </select>
    </div>
    <div class="td-field">
        <label>Direction</label>
        <div data-dir-group>
            <input type="hidden" data-field="direction" value="buy">
            <button type="button" class="dir-btn active-buy" data-dir="buy">Buy</button>
            <button type="button" class="dir-btn" data-dir="sell">Sell</button>
        </div>
    </div>
    <div class="td-field">
        <label>Entry Price</label>
        <input type="number" data-field="entry" step="0.00001" value="1.10000">
    </div>
    <div class="td-field">
        <label>Exit Price</label>
        <input type="number" data-field="exit" step="0.00001" value="1.10500">
    </div>
    <div class="td-field">
        <label>Lots</label>
        <input type="number" data-field="lots" step="0.01" value="0.10">
    </div>
    <div class="td-field">
        <label>Account Currency</label>
        <select data-field="account_currency">
            @foreach($currencies as $c)<option value="{{ $c }}" {{ $c==='USD' ? "selected" : "" }}>{{ $c }}</option>@endforeach
        </select>
    </div>
</div>
