@foreach ($form_fields as $field)
    <label>{{$field['label']}}</label>
    @unless ($field['required'] == '0')
        <span style="color:#FF0000">*</span>
    @endunless
    @isset($errors)
        <label style="color:#FF0000">{{$errors->first($field['id'])}}</label>
    @endisset
    <input class="input-txt" type="{{$field['type']}}" Placeholder="{{$field['placeholder']}}" id="{{$field['id']}}" name="{{$field['id']}}"
        value="{{old($field['id'])}}">
@endforeach