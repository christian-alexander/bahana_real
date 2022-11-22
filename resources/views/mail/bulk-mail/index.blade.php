<link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
@component('mail::message')
# All activity

<table class="table" style="width: 100%">
    <tbody>
        <tr>
            <th>No</th>
            <th>Activity</th>
        </tr>
    </tbody>
    <tbody>
        @foreach ($details as $item)
           <tr>
               <td>{{ $loop->iteration }}</td>
               <td>{{ $item->text }}</td>
            </tr> 
        @endforeach
    </tbody>
</table>

@lang('email.regards'),<br>
{{ config('app.name') }}
@endcomponent
