@extends('layouts.user')

@section('content')

        <div class="col-lg-9">

          <div class="transaction-area">
            <div class="heading-area">
              <h3 class="title">
                {{ __('Invests') }}
              </h3>
            </div>
            <div class="content">

							<div class="mr-table allproduct mt-4">
									<div class="table-responsiv">
											<table id="example" class="table table-hover dt-responsive" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th>{{ __('Plan') }}</th>
														<th>{{ __('Method') }}</th>
														<th>{{ __('Paid') }}</th>
														<th>{{ __('Time Left')}}</th>
														<th></th>
													</tr>
												</thead>
												<tbody>

												@foreach($orders as $data)
													<tr>
														<td>
																{{ $data->title }}
														</td>
														<td>
																{{ $data->method }}
														</td>
														<td>
															@if($gs->currency_format == 0)
																{{ $gs->currency_sign }}{{ round($data->invest , 2) }}
															@else 
																{{ round($data->invest , 2) }}{{ $gs->currency_sign }}
															@endif
														</td>

														<td class="countdown" data-date="{{ Carbon\Carbon::parse($data->end_date)->format('M d,Y h:i:s') }}">
																
														</td>
														<td>
															<a href="{{ route('user-order',$data->id) }}">
															{{ __('View More') }}
															</a>
														</td>

													</tr>
												@endforeach

												</tbody>
											</table>
									</div>
								</div>

            </div>
          </div>
        </div>

@endsection

@push('js')

<script type="text/javascript">
	'use strict';

	$('#example').DataTable({
		ordering: false
	});
</script>

<script type="text/javascript">
	'use strict';
	
	$('.countdown').each(function(){
		var date = $(this).data('date');
		var countDownDate = new Date(date).getTime();
		var $this = $(this);
		var x = setInterval(function() {
		  // Get today's date and time
		  var now = new Date().getTime();

		  // Find the distance between now and the count down date
		  var distance = countDownDate - now;

		  // Time calculations for days, hours, minutes and seconds
		  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
		  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

		  // Display the result in the element with id="demo"
		  var text = days + "d " + hours + "h "
		  + minutes + "m " + seconds + "s ";
		  $this.html(text);

		  // If the count down is finished, write some text 
		  if (distance < 0) {
		    clearInterval(x);
		   var text = 0 + "d " + 0 + "h "
		  + 0 + "m " + 0 + "s ";
		  $this.html(text);
		  }
		}, 1000);
	});


</script>


@endpush