<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="m-2 row">
                <div class="col-3">
                    <input type="text" value="Total Biding Amount {{ $bidding_amount }}" id="digit"
                        class="form-control" disabled>
                </div>
                <div class="col-3">
                    <input type="text" value="Total Winning Amount {{ $winning_amount }}" class="form-control"
                        disabled>
                </div>
            </div>
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table data-searching="false" data-paging="false" data-info="false"
                        data-order='[[2, "desc"], [0, "desc"]]' id="myTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Market Name</th>
                                <th>Number</th>
                                <th>Amount</th>
                                <th>Winnig Amount</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $result)
                                <tr>
                                    <td>{{ $result->get('user_name') }}</td>
                                    <td>{{ $result->get('market_name') }}</td>
                                    <td>{{ $result->get('number') }}</td>
                                    <td>{{ $result->get('amount') ?? 'NULL' }}</td>
                                    <td>{{ $result->get('win_amount') ?? 'NULL' }}</td>
                                    <td>{{ $result->get('created_at') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
