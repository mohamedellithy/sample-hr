<html>
    <head>
        <meta name="google-site-verification" content="40aCnX7tt4Ig1xeLHMATAESAkTL2pn15srB14sB-EOs" />
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />    
    </head>
    <body>
        <div class="container-fluid">
            <!-- DataTales Example -->
            <div class="card mb-4">
                <div class="card">
                    <div class="table-responsive text-nowrap">
                        <h4 class="card-header" style="text-align:center;font-size:10px;color:red">
                            القيود اليومية
                            <br/>
                            {{ $date_range}}
                        </h4>
                        <table class="table" cellpadding="3">
                            <thead class="table-light">
                                <tr class="table-dark" style="padding:10px;border:1px solid #171615;font-weight:600">
                                    <th style="border:1px solid #171615;padding:10px">التاريخ</th>
                                    <th style="border:1px solid #171615;padding:10px">البيان</th>
                                    <th style="border:1px solid #171615;padding:10px">الوصف</th>
                                    <th style="border:1px solid #171615;padding:10px">المدين</th>
                                    <th style="border:1px solid #171615;padding:10px">الدائن</th>
                                    <th style="border:1px solid #171615;padding:10px">الرصيد</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                    @php $balance = 0; @endphp
                                    @foreach($data as $item)
                                        @if(isset($item->item_expense_id))
                                            @php $balance -=$item->item_amount @endphp
                                            <tr style="border:1px solid gray;" height="25">
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ date('Y-m-d',strtotime($item->item_created_at)) }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    مصروفات
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ isset($item->item_name) ? $item->item_name : '-' }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{  '-' }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ $item->item_amount ?: '-' }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{  round($balance,3) }}
                                                </td>
                                            </tr>
                                        @elseif(isset($item->item_expenses_payments_id))
                                            @php $balance +=$item->item_amount @endphp
                                            <tr style="border:1px solid gray;">
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ date('Y-m-d',strtotime($item->item_created_at)) }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    مدفوعات للمصروفات
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    تسديد دفع من المصروفات
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ $item->item_amount ?: '-' }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{  '-' }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{  round($balance,3) }}
                                                </td>
                                            </tr>
                                        @elseif(isset($item->item_money_resources_id))
                                            @php $balance +=$item->item_amount @endphp
                                            <tr style="border:1px solid gray;">
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ date('Y-m-d',strtotime($item->item_created_at)) }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    الخزنة
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ get_resource_name($item->item_name) }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ $item->item_amount ?: '-' }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{  '-' }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{  round($balance,3) }}
                                                </td>
                                            </tr>
                                        @elseif(isset($item->item_sales_id))
                                            @php $balance -=$item->item_amount @endphp
                                            <tr style="border:1px solid gray;">
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ date('Y-m-d',strtotime($item->item_created_at)) }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    مبيعات
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    -
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ $item->item_amount ?: '-' }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{  '-' }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{  round($balance,3) }}
                                                </td>
                                            </tr>
                                        @elseif(isset($item->item_sales_payments_id))
                                            @php $balance +=$item->item_amount @endphp
                                            <tr style="border:1px solid gray;">
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ date('Y-m-d',strtotime($item->item_created_at)) }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    تحصيل مدفوعات المبيعات 
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ $item->item_description ?: '-' }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{  '-' }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ $item->item_amount ?: '-' }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{  round($balance,3) }}
                                                </td>
                                            </tr>
                                        @elseif(isset($item->item_employee_advances_id))
                                            @php $balance -=$item->item_amount @endphp
                                            <tr style="border:1px solid gray;">
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ date('Y-m-d',strtotime($item->item_created_at)) }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    سلفه لموظف
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ $item->item_description ?: '-' }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ $item->item_amount ?: '-' }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{  '-' }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{  round($balance,3) }}
                                                </td>
                                            </tr>
                                        @elseif(isset($item->item_employee_paids_id))
                                            @php $balance -=$item->item_amount @endphp
                                            <tr style="border:1px solid gray;">
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ date('Y-m-d',strtotime($item->item_created_at)) }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    تسديد رواتب الموظف
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ $item->item_description ?: '-' }}
                                                    عن شهر
                                                    {{ $item->item_month ?: '-' }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{ $item->item_amount ?: '-' }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{  '-' }}
                                                </td>
                                                <td style="border-bottom:1px solid #171615;border:1px solid #171615;font-size:10px">
                                                    {{  round($balance,3) }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>