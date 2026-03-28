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
            <div class="card-body p-0" id="table-start-div">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>User Name</th>
                            <th>Market Name</th>
                            <th>Number</th>
                            <th>Amount</th>
                            <th>Winnig Amount</th>
                            <th>Created At</th>
                        </tr>
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
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
