<table class="table">
     <caption>{{ $payments->appends($_GET)->links() }}</caption>
     <thead>
         <tr>
             <th>DateTime</th>                               
             <th>Merchant</th>
             <th>Customer</th>
             <th>Transaction ID</th>
             <th>Merchant Description</th>
             <th>Status</th>
         </tr>
     </thead>
     <tbody>
     @foreach($payments AS $payment)
    <?php $class = 'alert alert-danger'; ?>
    @if(strtolower($payment->status) == 'ok' || strtolower($payment->status) =='approved')
          <?php $class = 'alert alert-success'; ?>
    @endif

         <tr >
             <td>{{ $payment->txn_datetime  }}</td>
             <td>{{ $payment->merchant_name  }}</td>
             <td>{{ $payment->subs_account  }}</td>
             <td>{{ $payment->PaymentSpTxId  }}</td>
             <td>{{ $payment->merchant_description  }}</td>
             <td class="{{ $class }}">{{ $payment->status  }}</td>
         </tr>
     @endforeach
     </tbody>
 </table>