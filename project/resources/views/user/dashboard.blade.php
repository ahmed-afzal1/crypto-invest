@extends('layouts.user')

@section('content')

        <div class="col-lg-9">

          <div class="transaction-area">
            <div class="heading-area">
              <h3 class="title">
                {{ __('Recent Transactions') }}
              </h3>
            </div>
			<br>

			<div class="current-balance mt-30 text-center">
				<div class="row mx-auto">
					<div class="col-lg-5 left-amount">
						<div class="icon-area">
							<div class="icon">
								<img  src="{{ asset('assets/images/balance-icon.png') }}" alt="">
							</div>
						</div>

						<div class="content ">
							@if($gs->currency_format == 0)
								<p class="amount">{{ $gs->currency_sign }}{{ round(Auth::user()->income,2) }}</p>
							@else
								<p class="amount">{{ round(Auth::user()->income,2) }}{{ $gs->currency_sign }}</p>
							@endif
								<span class="label">{{__('Current Balance')}}</span>
						</div>
					</div>

					<div class="col-lg-5  left-amount">
						<div class="icon-area">
							<div class="icon">
								<img  src="{{ asset('assets/images/balance-icon.png') }}" alt="">
							</div>
						</div>

						<div class="content ">
							@if($gs->currency_format == 0)
								<p class="amount">{{ $gs->currency_sign }}{{ round($investAmount,2) }}</p>
							@else
								<p class="amount">{{ round($investAmount,2) }}{{ $gs->currency_sign }}</p>
							@endif
								<span class="label">{{__('Invest Amount')}}</span>
						</div>
					</div>
				</div>
			</div>

            <div class="content">
				<div class="mr-table allproduct mt-5">
					<div class="table-responsiv">
							<table id="example" class="table  table-hover dt-responsive tabl-text" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>{{ __('Type') }}</th>
										<th>{{ __('Txnid') }}</th>
										<th>{{ __('Amount') }}</th>
										<th>{{ __('Date') }}</th>
									</tr>
								</thead>

								<tbody>
									@foreach($user->transactions()->orderBy('id','desc')->take(5)->get() as $data)
									<tr>
										<td>
											{{$data->type}}
										</td>

										<td>
											{{ $data->txnid }}
										</td>

										<td>
											@if($gs->currency_format == 0)
												{{$gs->currency_sign}}{{ round($data->amount , 2) }}
											@else
												{{ round($data->amount , 2) }}{{$gs->currency_sign}}
											@endif
										</td>

										<td>
											{{date('d M Y',strtotime($data->created_at))}}
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
	$('#example').DataTable();
</script>

@endpush
