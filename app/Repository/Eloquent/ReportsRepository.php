<?php


namespace App\Repository\Eloquent;


use App\Repository\ReportsRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ReportsRepository implements ReportsRepositoryInterface
{

    public function generate($from = null, $to = null)
    {
        $sql = "select a.date as `Date`, c.country as `Country`, 
            count(distinct(a.customer_id)) as `Unique Customers`,
                (select count(*) from actions where type = 1 and date = a.date and customer_id = a.customer_id) as `No of Deposits`,
                (select sum(amount) from actions where type = 1  and date = a.date and customer_id = a.customer_id) as `Total Deposit Amount`,
                (select count(*) from actions where type = 0 and date = a.date  and customer_id = a.customer_id) as `No of withdrawals`,
                (select sum(amount) from actions where type = 0 and date = a.date and customer_id = a.customer_id) as `Total Withdrawal Amount`    
                    from actions a		
                join customers c on a.customer_id = c.id
                
                group by a.date, c.country, a.customer_id";

        return DB::select($sql);
    }
}