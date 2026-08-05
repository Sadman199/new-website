<x-admin.page-header title="Site Settings" description='Table: <code>settings</code> — single row (id: 1)'>
    <x-slot:actions>
        <button class="btn-bc btn-bc-primary" type="submit" form="settings-form">
            <i class="fas fa-save"></i> Save Settings
        </button>
    </x-slot:actions>
</x-admin.page-header>

<div class="bc-card">
    <form id="settings-form" action="{{ route('admin_setting_update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bc-card-body">
            <div class="form-grid">
                <div class="form-group">
                    <label for="logo">logo</label>
                    <input class="form-control-bc" type="text" name="logo" id="logo" value="{{ old('logo', $setting->logo ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="favicon">favicon</label>
                    <input class="form-control-bc" type="text" name="favicon" id="favicon" value="{{ old('favicon', $setting->favicon ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="theme_color_1">theme_color_1</label>
                    <input class="form-control-bc" type="text" name="theme_color_1" id="theme_color_1" value="{{ old('theme_color_1', $setting->theme_color_1 ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="theme_color_2">theme_color_2</label>
                    <input class="form-control-bc" type="text" name="theme_color_2" id="theme_color_2" value="{{ old('theme_color_2', $setting->theme_color_2 ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="news_ticker_total">news_ticker_total</label>
                    <input class="form-control-bc" type="text" name="news_ticker_total" id="news_ticker_total" value="{{ old('news_ticker_total', $setting->news_ticker_total ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="news_ticker_status">news_ticker_status</label>
                    <select class="form-control-bc" name="news_ticker_status" id="news_ticker_status">
                        <option value="Show" @selected(old('news_ticker_status', $setting->news_ticker_status ?? '') === 'Show')>Show</option>
                        <option value="Hide" @selected(old('news_ticker_status', $setting->news_ticker_status ?? '') === 'Hide')>Hide</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="video_total">video_total</label>
                    <input class="form-control-bc" type="text" name="video_total" id="video_total" value="{{ old('video_total', $setting->video_total ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="video_status">video_status</label>
                    <select class="form-control-bc" name="video_status" id="video_status">
                        <option value="Show" @selected(old('video_status', $setting->video_status ?? '') === 'Show')>Show</option>
                        <option value="Hide" @selected(old('video_status', $setting->video_status ?? '') === 'Hide')>Hide</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="top_bar_date_status">top_bar_date_status</label>
                    <select class="form-control-bc" name="top_bar_date_status" id="top_bar_date_status">
                        <option value="Show" @selected(old('top_bar_date_status', $setting->top_bar_date_status ?? '') === 'Show')>Show</option>
                        <option value="Hide" @selected(old('top_bar_date_status', $setting->top_bar_date_status ?? '') === 'Hide')>Hide</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="top_bar_email">top_bar_email</label>
                    <input class="form-control-bc" type="email" name="top_bar_email" id="top_bar_email" value="{{ old('top_bar_email', $setting->top_bar_email ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="top_bar_email_status">top_bar_email_status</label>
                    <select class="form-control-bc" name="top_bar_email_status" id="top_bar_email_status">
                        <option value="Show" @selected(old('top_bar_email_status', $setting->top_bar_email_status ?? '') === 'Show')>Show</option>
                        <option value="Hide" @selected(old('top_bar_email_status', $setting->top_bar_email_status ?? '') === 'Hide')>Hide</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="analytic_id">analytic_id</label>
                    <input class="form-control-bc" type="text" name="analytic_id" id="analytic_id" value="{{ old('analytic_id', $setting->analytic_id ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="analytic_status">analytic_status</label>
                    <select class="form-control-bc" name="analytic_status" id="analytic_status">
                        <option value="Show" @selected(old('analytic_status', $setting->analytic_status ?? '') === 'Show')>Show</option>
                        <option value="Hide" @selected(old('analytic_status', $setting->analytic_status ?? '') === 'Hide')>Hide</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="disqus_code">disqus_code</label>
                <textarea class="form-control-bc" name="disqus_code" id="disqus_code" rows="2">{{ old('disqus_code', $setting->disqus_code ?? '') }}</textarea>
            </div>
        </div>
    </form>
</div>
