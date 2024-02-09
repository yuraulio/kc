<?php

namespace Apifon\Model;

use Exception;

class SubscribersViewRequest implements IRequest
{
    public $subscribers = [];

    /**
     * Adds subscribers to the existing subscriber list.
     * @param $subscribers
     */
    public function addSubscribers($subscribers)
    {
        if (is_array($subscribers)) {
            $this->subscribers = $subscribers;
        } elseif (is_string($subscribers)) {
            $this->subscribers = json_decode($subscribers, true);
        } else {
            var_dump('Subscriber entry is not valid');
            die();
        }
    }

    /**
     * Adds subscribers to the existing subscriber list.
     * Input is a list of phone numbers.
     * @param $subscribers
     */
    public function addStrSubscribers($subscribers)
    {
        foreach ($subscribers as $sub) {
            $subscriber = new SubscriberInformation();
            $subscriber->setNumber($sub);
            $this->subscribers[] = $subscriber;
        }
    }

    /**
     * Adds a subscriber to the existing subscriber list.
     * Input is either a subscriber or a phone number.
     * @param $subscriber
     */
    public function addSubscriber($subscriber)
    {
        if ($subscriber instanceof SubscriberInformation) {
            $this->subscribers[] = $subscriber;
        } elseif (is_string($subscriber)) {
            $sub = new SubscriberInformation();
            $sub->setNumber($subscriber);
            $this->subscribers[] = $sub;
        }
    }

    public function getSubscribers()
    {
        return $this->subscribers;
    }

    public function setSubscribers($subscribers)
    {
        $this->subscribers = $subscribers;
    }

    /**
     * Sets the subscriber list.
     * Input is a list of phone numbers.
     * @param $subscribers
     */
    public function setStrSubscribers($subscribers)
    {
        foreach ($subscribers as $subscriber) {
            $sub = new SubscriberInformation();
            $sub->setNumber($subscriber);
            $subsList[] = $sub;
        }
        $this->subscribers = $subsList;
    }

    public function getBody()
    {
        throw new Exception('Not implemented');
    }

    public function getCreateBody()
    {
        throw new Exception('Not implemented');
    }

    public function getReadBody()
    {
        throw new Exception('Not implemented');
    }

    public function getUpdateBody()
    {
        throw new Exception('Not implemented');
    }

    public function getDeleteBody()
    {
        throw new Exception('Not implemented');
    }
}
