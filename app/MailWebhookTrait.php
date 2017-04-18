<?php namespace App;

use App\Stat;

trait MailWebhookTrait {

    private function _gStat()
    {
        if(!is_null($this->getStats()))
            $stat =  $this->getStats();


        if(Carbon::now()->subHours(1)->lt(Carbon::parse($stat->created_at)))
            return $stat;

        $nstat = new Stat;
        $nstat->deliveries = 0;
        $nstat->spam_complaints = 0;
        $nstat->clicks = 0;
        $nstat->opens = 0;

        $this->stats()->save($nstat);


        return $nstat;
    }
    /**
     * Increment the models delivery count by 1
     * @return self
     */
	public function incrementDeliveries()
    {

        $stat = $this->_gStat();

        $stat->deliveries++;
        $stat->save();


        return $this;
    }
    /**
     * Increment the models complaint count by 1
     * @return self
     */
    public function incrementComplaints()
    {
        $stat = $this->_gStat();

        $stat->spam_complaints++;
        $stat->save();

        return $this;
    }
    /**
     * Increment the models click count by 1
     * @return self
     */
    public function incrementClicks()
    {
        $stat = $this->_gStat();

        $stat->clicks++;
        $stat->save();

        return $this;
    }
    /**
     * Increment the models open count by 1
     * @return self
     */
    public function incrementOpens()
    {
        $stat = $this->_gStat();

        $stat->opens++;
        $stat->save();

        return $this;
    }
}