@php

$pay_data = $gateway->convertAutoData();

@endphp

@if($payment == 'paypal')

  <input type="hidden" name="method" value="{{ $gateway->name }}">

@endif

@if($payment == 'stripe')

  <input type="hidden" name="method" value="{{ $gateway->name }}">

  <div class="row">

    <div class="col-lg-6">
      <input class="form-control card-elements mb-2" name="cardNumber" type="text" placeholder="{{ __('Card Number') }}" autocomplete="off"  autofocus oninput="validateCard(this.value);" />
      @if ($errors->has('cardNumber'))
        <p>{{$errors->first('cardNumber')}}</p>
      @endif
    </div>

    <div class="col-lg-6">
      <input class="form-control card-elements mb-2" name="cardCVC" type="text" placeholder="{{ __('Cvv') }}" autocomplete="off"  oninput="validateCVC(this.value);" />
      @if ($errors->has('cardCVC'))
        <p>{{$errors->first('cardCVC')}}</p>
      @endif
    </div>

    <div class="col-lg-6">
       <input class="form-control card-elements" name="month" type="text" placeholder="{{ __('Month') }}"  />
       @if ($errors->has('month'))
         <p>{{$errors->first('month')}}</p>
       @endif
    </div>

    <div class="col-lg-6">
      <input class="form-control card-elements" name="year" type="text" placeholder="{{ __('Year')}}"  />
      @if ($errors->has('year'))
        <p>{{$errors->first('year')}}</p>
      @endif
    </div>

  </div>

@endif


 @if($payment == 'instamojo')

  <input type="hidden" name="method" value="{{ $gateway->name }}">

@endif
