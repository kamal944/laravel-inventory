
<style>
    #categories {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #categories td, #categories th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #categories tr:nth-child(even){background-color: #f2f2f2;}

    #categories tr:hover {background-color: #ddd;}

    #categories th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }
</style>

<table id="categories" width="100%">
    <thead>
    <tr>
        <td>ID</td>
        <td>Name</td>
        <td>Address</td>
        <td>Email</td>
    </tr>
    </thead>
    @foreach($suppliers as $s)
        <tbody>
        <tr>
            <td>{{ $s->id }}</td>
            <td>{{ $s->name }}</td>
            <td>{{ $s->address }}</td>
            <td>{{ $s->email }}</td>
        </tr>
        </tbody>
    @endforeach

</table>


