<!DOCTYPE html>
<html>

<head>
    <title>Preview Journal Voucher Details</title>
</head>

<body style="font-family: sans-serif; margin: 0; padding: 0; font-size: 12pt;">
    <div class="container" style="margin: 0 auto;">
        <h2 class="text-center" style="text-align: center; margin-bottom: 0.5in;">{{ $headingText }}</h2>
        <table style="width: 100%; margin-bottom: 0.1in; border-collapse: collapse;">
            <tr>
                <td style="width: 20%; text-align: right; font-weight: bold; padding-right: 0.1in;">Voucher Number:</td>
                <td style="width: 30%;">{{ $voucher->voucher_number }}</td>
                <td style="width: 20%; text-align: right; font-weight: bold; padding-right: 0.1in;">Company Name:</td>
                <td style="width: 30%;">{{ $company->company_name }}</td>
            </tr>
            <tr>
                <td style="text-align: right; font-weight: bold; padding-right: 0.1in;">Voucher Type:</td>
                <td>{{ $voucher->voucher_type }}</td>
                <td style="text-align: right; font-weight: bold; padding-right: 0.1in;">Date:</td>
                <td>{{ \Carbon\Carbon::parse($voucher->created_at)}}</td>
            </tr>
            <tr>
                <td style="text-align: right; font-weight: bold; padding-right: 0.1in;">Prepared By:</td>
                <td>{{ $voucher->prepared_by }}</td>
                <td style="text-align: right; font-weight: bold; padding-right: 0.1in;">Approved By (Optional):</td>
                <td>{{ $voucher->approved_by }}</td>
            </tr>
        </table>
        <div class="row description" style="display: block; margin-bottom: 0.1in;">
            <div class="description-label" style="font-weight: bold; display: block; margin-bottom: 0.05in;">Description:</div>
            <div class="description-value" style="display: block;">{{ $voucher->description }}</div>
        </div>

        <div class="table-responsive" style="overflow-x: auto;">
            <table class="table table-bordered" id="voucherDetailsTable" style="width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 0.2in;">
                <thead>
                    <tr class="text-center" style="background-color: #f2f2f2;">
                        <th style="border: 1px solid black; padding: 0.1in; word-wrap: break-word; text-align: center;">Account Code</th>
                        <th style="border: 1px solid black; padding: 0.1in; word-wrap: break-word; text-align: center;">Account Name</th>
                        <th style="border: 1px solid black; padding: 0.1in; word-wrap: break-word; text-align: center;">Debit</th>
                        <th style="border: 1px solid black; padding: 0.1in; word-wrap: break-word; text-align: center;">Credit</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($details as $detail)
                    <tr>
                        <td style="border: 1px solid black; padding: 0.1in; word-wrap: break-word; text-align: center;">{{ $detail->account_code }}</td>
                        <td style="border: 1px solid black; padding: 0.1in; word-wrap: break-word; text-align: center;">{{ $detail->account_name }}</td>
                        <td style="border: 1px solid black; padding: 0.1in; word-wrap: break-word; text-align: center;">{{ number_format($detail->debit, 2) }}</td>
                        <td style="border: 1px solid black; padding: 0.1in; word-wrap: break-word; text-align: center;">{{ number_format($detail->credit, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="total-section" style="display: flex; justify-content: flex-end; margin-bottom: 0.2in;">
            <div class="total-label" style="font-weight: bold; margin-right: 0.1in;">Total Debit:</div>
            <div class="total-value" style="margin-right: 0.5in;">{{ number_format($details->sum('debit'), 2) }}</div>
            <div class="total-label" style="font-weight: bold; margin-right: 0.1in;">Total Credit:</div>
            <div class="total-value" style="margin-right: 0.5in;">{{ number_format($details->sum('credit'), 2) }}</div>
        </div>

        <table class="signature-table" style="margin-left: auto; width: 60%; border-collapse: collapse;">
            <thead>
                <tr class="text-center">
                    <th style="text-align: center; border: 1px solid black; padding: 0.1in;">Created By</th>
                    <th style="text-align: center; border: 1px solid black; padding: 0.1in;">Checked By</th>
                    <th style="text-align: center; border: 1px solid black; padding: 0.1in;">Reviewed By</th>
                    <th style="text-align: center; border: 1px solid black; padding: 0.1in;">Approved By</th>
                </tr>
            </thead>
            <tr>
                <td style="width: 25%; height: 1in; border: 1px solid black; padding: 0.1in;"></td>
                <td style="width: 25%; height: 1in; border: 1px solid black; padding: 0.1in;"></td>
                <td style="width: 25%; height: 1in; border: 1px solid black; padding: 0.1in;"></td>
                <td style="width: 25%; height: 1in; border: 1px solid black; padding: 0.1in;"></td>
            </tr>
        </table>
    </div>
</body>

</html>