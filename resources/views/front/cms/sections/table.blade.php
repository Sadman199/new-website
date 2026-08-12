@php $d = $data ?? []; @endphp
<section class="cms-section cms-table-section">
    <div class="cms-wrap">
        @if(!empty($d['heading']))
            <h2 class="cms-section__title">{{ $d['heading'] }}</h2>
        @endif
        @if(!empty($d['headers']) || !empty($d['rows']))
            <div class="cms-table-wrap">
                <table class="cms-table">
                    @if(!empty($d['caption']))
                        <caption>{{ $d['caption'] }}</caption>
                    @endif
                    @if(!empty($d['headers']))
                        <thead>
                            <tr>
                                @foreach($d['headers'] as $header)
                                    <th scope="col">{{ $header }}</th>
                                @endforeach
                            </tr>
                        </thead>
                    @endif
                    @if(!empty($d['rows']))
                        <tbody>
                            @foreach($d['rows'] as $row)
                                <tr>
                                    @foreach($row as $cell)
                                        <td>{{ $cell }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
            </div>
        @endif
    </div>
</section>
