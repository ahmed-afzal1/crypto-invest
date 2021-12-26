@extends('layouts.user')

@section('content')

        <div class="col-lg-9">

          <div class="transaction-area">
            <div class="heading-area">
              <h3 class="title">
                {{ __('Investment Details') }} <a href="javascript:history.back();" class="btn btn-round btn-base ml-2">{{ __('Back') }}</a>
              </h3>
            </div>
            <div class="content">
                <div class="mr-table allproduct mt-4">
                    <div class="table-responsiv">
                        <table class="table">
                            <tr>
                                <th width="50%">{{ __('Transaction ID') }}</th>
                                <td>{{$order->order_number}}</td>
                            </tr>

                            <tr>
                                <th>{{ __('Pricing Plan') }}</th>
                                <td>{{$order->title}}</td>
                            </tr>

                            <tr>
                                <th>{{ __('Payment Method') }}</th>
                                <td>{{$order->method}}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Invest') }}</th>
                                <td>{{$order->invest}}{{ $gs->currency_sign }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Get') }}</th>
                                <td>{{$order->pay_amount}}{{ $gs->currency_sign }}</td>
                            </tr>
                            <tr>
                                <th>{{$order->method}} {{ __('Transaction ID') }}</th>
                                <td>{{$order->txnid}}</td>
                            </tr>

                            @if($order->method != 'Paypal')
                            <tr>
                                <th>{{$order->method}} {{ __('Transaction ID') }}</th>
                                <td>{{$order->method}} {{$order->charge_id}}</td>
                            </tr>
                            @endif

                            <tr>
                                <th>{{ __('Payment Status') }}</th>
                                <td>{{$order->payment_status}}</td>
                            </tr>


                            <tr>
                                <th>{{ __('Date') }}</th>
                                <td>{{$order->created_at}}</td>
                            </tr>


                            
                        </table>
                    </div>
                </div>
            </div>
          </div>
        </div>

@endsection

@push('js')
<script type="text/javascript">
    $('#example').DataTable({
        ordering: false
    });
</script>
@endpush
