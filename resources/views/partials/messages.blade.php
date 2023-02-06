@isset($response)
    <div class="alert {{ ($response['response'] == 'success') ? 'alert-success' : 'alert-danger' }}">
        {{ $response['message'] }}
    </div>
@endisset