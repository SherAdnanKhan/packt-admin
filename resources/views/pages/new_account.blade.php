@extends('layout')

@section('title')
    {{$data['title']}}
@endsection
@section('customcss')
<style>
   .form label {
  margin-bottom: 0.4rem;
}
</style>

@endsection

@section('content')
    <form class="form" id="createOrderForm" action='{{ action('AccountController@register') }}' method="POST">
        {{ csrf_field() }}
        <p>User Information</p>
        <hr/>
        <div class="container user-account-info-container">
            <div class="brow">
                @foreach ($data['form_fields'] as $field)
                    <div class="col-6 flexDisplay">
                        <div class="col-6">
                            <label>{{ $field['label'] }}
                                @unless ($field['required'] == '0')
                                    <span style="color:#FF0000">*</span>
                                @endunless
                            </label>
                        </div>
                        <div class="col-6">
                            @if ($field['id'] == 'discountGroup')
                                @include('partials.dropdown')
                            @elseif ($field['id'] == 'currency')
                                <select class="btn--dropdown" name="{{$field['id']}}" id="{{$field['id']}}">
                                    <option value="USD">USD</option>
                                    <option value="AUD">AUD</option>
                                    <option value="INR">INR</option>
                                    <option value="EUR">EUR</option>
                                </select>
                            @else
                                <input class="input-txt" Placeholder="{{$field['placeholder']}}" type="{{$field['type']}}" id="{{$field['id']}}" name="{{$field['id']}}"
                                    value="{{old($field['id'])}}">
                            @endif
                            @isset($errors)
                                <label class="error" style="color:#FF0000">{{$errors->first($field['id'])}}</label>
                            @endisset
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <p>Address Information</p>
        <hr>
        @include('partials.addresses')

        <div class="container">
            <div class="brow">
                <div class="col-6">
                    @include('partials.recaptcha-widget')
                </div>
            </div>
        </div>
        <div class="container">
            <button class="btn" id="button-submit-order" type="submit" name="register"> Register </button>
        </div>
    </form>
@endsection

@section('pageLevelJs')
<script src="https://cdn.jsdelivr.net/npm/algoliasearch@3/dist/algoliasearchLite.min.js"></script>
<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script>
    $( document )
    .on('change', 'input[name="billAsShip"]', function() {
        var elems = document.querySelectorAll(".shipVisible");
        if(this.checked) {
            [].forEach.call(elems, function(el) {
                el.classList.add("hide");
            });
        }
        else {
            [].forEach.call(elems, function(el) {
                el.classList.remove("hide");
            });
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.min.js"></script>
<script src="{{asset('js/new_account.js')}}"> </script>
<script src="{{asset('js/country_city.js')}}"> </script>


@endsection
