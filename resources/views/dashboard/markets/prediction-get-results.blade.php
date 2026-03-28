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
                            <th>Action</th>
                            <th>Created At</th>
                        </tr>
                        @foreach ($results as $result)
                            <tr>
                                <td>{{ $result->get('user_name') }}</td>
                                <td>{{ $result->get('market_name') }}</td>
                                <td id="bid_number_{{ $result->get('id') }}">{{ $result->get('number') ?? 'NULL' }}</td>
                                <td id="bid_amount_{{ $result->get('id') }}">{{ $result->get('amount') ?? 'NULL' }}</td>
                                <td>{{ $result->get('win_amount') ?? 'NULL' }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#exampleModal"
                                        onclick="update_game_id({{ $result->get('id') }})">Edit</button>
                                </td>
                                <td>{{ $result->get('created_at') }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
