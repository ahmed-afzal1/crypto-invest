@extends('layouts.user')

@section('content')

        <div class="col-lg-9">

          <div class="transaction-area">
            <div class="heading-area">
              <h3 class="title">
                {{ __('Payouts') }}
              </h3>
            </div>
            <div class="content">
				@if (count($orders) == 0)
					<div class="row justify-content-md-center">
						<p>{{__('NO PAYOUT FOUND')}}</p>
					</div>
				@else 
				<div class="mr-table allproduct mt-4">
					<div class="table-responsiv">
						<table id="example" class="table tabl-text table-hover dt-responsive" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>{{ __('Plan') }}</th>
									<th>{{ __('Method') }}</th>
									<th>{{ __('Paid') }}</th>
									<th>{{ __('Get') }}</th>
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

										<td>
											@if($gs->currency_format == 0)
												{{ $gs->currency_sign }}{{ round($data->pay_amount , 2) }}
											@else
												{{ round($data->pay_amount , 2) }}{{ $gs->currency_sign }}
											@endif
										</td>
										
										<td>
											<a href="{{ route('user-order',$data->id) }}">
											{{ __('VIEW MORE') }}
											</a>
										</td>

									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
				@endif
            </div>
          </div>
        </div>

@endsection

@section('scripts')

<script type="text/javascript">
	$('#example').DataTable({
		ordering: false
	});
</script>

@endsection
