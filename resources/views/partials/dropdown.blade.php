<select class="btn--dropdown" name="{{$dropdown['name']}}" id="{{$dropdown['id']}}">
    @foreach ($dropdown['options'] as $option)
        <option value="{{$option['value']}}">{{$option['text']}}</option>
    @endforeach
</select>
