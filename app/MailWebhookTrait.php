<?php namespace App;

trait MailWebhookTrait {

    /**
     * Increment the models delivery count by 1
     * @return self
     */
	public function incrementDeliveries()
    {
        $this->attributes['deliveries']++;
        $this->save();

        return $this;
    }
    /**
     * Increment the models complaint count by 1
     * @return self
     */
    public function incrementComplaints()
    {
        $this->attributes['spam_complaints']++;
        $this->save();

        return $this;
    }
    /**
     * Increment the models click count by 1
     * @return self
     */
    public function incrementClicks()
    {
        $this->attributes['clicks']++;
        $this->save();

        return $this;
    }
    /**
     * Increment the models open count by 1
     * @return self
     */
    public function incrementOpens()
    {
        $this->attributes['opens']++;
        $this->save();

        return $this;
    }
}