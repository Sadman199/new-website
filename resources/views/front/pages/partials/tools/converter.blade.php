<div class="td-grid td-grid-2">
    <div class="td-field">
        <label>Amount</label>
        <input type="number" data-field="amount" step="0.01" value="1000">
    </div>
    <div class="td-field">
        <label>From</label>
        <select data-field="from">
            @foreach($currencies as $c)<option value="{{ $c }}" {{ $c==='USD' ? "selected" : "" }}>{{ $c }}</option>@endforeach
        </select>
    </div>
    <div class="td-field">
        <label>To</label>
        <select data-field="to">
            @foreach($currencies as $c)<option value="{{ $c }}" {{ $c==='EUR' ? "selected" : "" }}>{{ $c }}</option>@endforeach
        </select>
    </div>
</div>
