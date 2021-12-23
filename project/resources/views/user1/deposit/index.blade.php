@extends('layouts.user')

@section('content')

        <div class="col-lg-9">
          <div class="transaction-area">
            <div class="heading-area">
              <h3 class="title">
                {{ __('My Deposits') }} <a href="{{route('user.deposit.create')}}" class="btn btn-primary btn-round ml-2">{{ __('Deposit Now') }}</a>
              </h3>
            </div>
            <div class="content">
                <div class="mr-table allproduct mt-4">
                    <div class="table-responsiv">
                        <table id="example" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Deposit Date') }}</th>
                                    <th>{{ __('Method') }}</th>
                                    <th>{{ __('Account') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deposits as $deposit)
                                    <tr>
                                        <td>{{date('d-M-Y',strtotime($deposit->created_at))}}</td>
                                        <td>{{$deposit->method}}</td>
                                        <td>{{ auth()->user()->email }}</td>
                                        @if($gs->currency_format == 0)
                                            <td>{{ $gs->currency_sign }} {{ round($deposit->amount, 2) }}</td>
                                        @else 
                                            <td>{{ round($deposit->amount, 2) }}{{$gs->currency_sign}}</td>
                                        @endif
                                        <td>{{ ucfirst($deposit->status) }}</td>
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

@section('scripts')

<script type="text/javascript">
    $('#example').DataTable({
        ordering: false
    });
</script>

@endsection

