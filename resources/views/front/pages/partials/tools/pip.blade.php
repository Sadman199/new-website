<div class="td-grid td-grid-2">
    <div class="td-field">
        <label>Currency Pair</label>
        <select data-field="pair">
            @foreach($pairs as $p)<option value="{{ $p }}">{{ $p }}</option>@endforeach
        </select>
    </div>
    <div class="td-field">
        <label>Lots</label>
        <input type="number" data-field="lots" step="0.01" value="1" min="0.01">
    </div>
    <div class="td-field">
        <label>Account Currency</label>
        <select data-field="account_currency">
            @foreach($currencies as $c)<option value="{{ $c }}" {{ $c==='USD' ? "selected" : "" }}>{{ $c }}</option>@endforeach
        </select>
    </div>
    <div class="td-field">
        <label>Market Price (optional)</label>
        <input type="number" data-field="price" step="0.00001" placeholder="Auto if empty">
    </div>
</div>
