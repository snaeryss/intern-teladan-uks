<table aria-label="export student account">
    <thead>
    <tr>
        <th colspan="4" scope="col">Student Class : {{ $class_name }}</th>
    </tr>
    <tr>
        <th scope="col">No</th>
        <th scope="col"><b>Nama</b></th>
        <th scope="col"><b>Username</b></th>
        <th scope="col"><b>Password</b></th>
    </tr>
    </thead>
    <tbody>
    @php
        $i = 1;
    @endphp
    @foreach($accounts as $account)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $account->name }}</td>
            <td>{{ $account->nis }}</td>
            <td>{{ $account->decrypt_secret }}</td>
        </tr>
    @endforeach
    </tbody>
</table>