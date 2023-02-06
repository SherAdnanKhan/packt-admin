<div class="form-row">
 @foreach($address_types as $address)
  @if ($address['prefix'] == 'ship_')
  
  <div class="billAsShip">
   <input type="checkbox" id="billAsShip" name="billAsShip" value="1" checked>
   <label for="billAsShip"> Shipping same as Billing Address</label>
  </div>
  <br/>
  <div class="addressDiv container hide shipVisible">
   <div class="brow" id="shippingAddress">
   
   </div>
  </div>
  <div class="brow"></div>

  @endif
  <h3 class="@if( $address['prefix'] == 'ship_') hide shipVisible @endif">{{ $address['title'] }}</h3>
  <div class="brow @if( $address['prefix'] == 'ship_') hide shipVisible @endif"  >
   @foreach($address_fields as $field)
    <div class="col-6 flexDisplay">
     <div class="col-6"> 
      <label>{{ $field['label'] }}
       @unless ($field['required'] == '0')
        <span style="color:#FF0000">*</span>
       @endunless
      </label>
     </div>
     <div class="col-6">
      @if($field['type'] == 'select')
        <select class="input-txt" id="{{$address['prefix'] . $field['id']}}" class="{{$field['id']}}_select" name="{{$address['prefix'] . $field['id']}}">
        <option value="">Select {{$field['label']}}</option>
        
          @if($field['id'] == 'country')
            @foreach ($countries as $country) 
            <option country="{{$country['name']['en']}}" alpha='{{trim(ucwords($country["code_alpha3"]))}}' value="{{$country['id']}}">
            {{$country['name']['en']}}
            </option>
            @endforeach
          @endif
        </select>

      @else
        <input class="input-txt" type="{{$field['type']}}" Placeholder="{{$field['placeholder']}}" id="{{$address['prefix'] . $field['id']}}" name="{{$address['prefix'] . $field['id']}}"
        value="{{old($address['prefix'] . $field['id'])}}">
      @endif
     </div>
    </div>
   @endforeach
  </div>
 @endforeach
</div>