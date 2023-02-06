<div class="form-group">
    <div class="col-6">
        <div class="g-recaptcha" data-sitekey="{{env('MIX_RECAPTCHA_PUBLIC_KEY')}}"></div>
        @isset($errors)
            @if ($errors->has('g-recaptcha-response'))
                <p style="color:#FF0000">{{$errors->first('g-recaptcha-response')}}</p>                    
            @endif
        @endisset
    </div>
</div>