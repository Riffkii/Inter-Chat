<?php

namespace Web\InterChat\Model\Response;
use Web\InterChat\Model\Database\Notification;

class AddNotificationResponse {

    private Notification $notification;

    public function setNotification(Notification $notification) {
        $this->notification = $notification;
    }

    public function getNotification() {
        return $this->notification;
    }
}