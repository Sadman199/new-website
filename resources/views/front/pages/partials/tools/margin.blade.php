<div class="td-grid td-grid-2">
    <div class="td-field">
        <label>Currency Pair</label>
        <select data-field="pair">
            @foreach($pairs as $p)<option value="{{ $p }}">{{ $p }}</option>@endforeach
        </select>
    </div>
    <div class="td-field">
        <label>Lots</label>
        <input type="number" data-field="lots" step="0.01" value="1">
    </div>
    <div class="td-field">
        <label>Leverage</label>
        <select data-field="leverage">
            @foreach([10,20,30,50,100,200,500] as $lev)
                <option value="{{ $lev }}" {{ $lev===100 ? "selected" : "" }}>1:{{ $lev }}</option>
            @endforeach
        </select>
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
