<?php

namespace Web\InterChat\Model\Database;
use JsonSerializable;

class Notification implements JsonSerializable {
    
    private int $id;
    private string $username;
    private string $messageFrom;
    private string $message;

    public function setID(int $id) {
        $this->id = $id;
    }

    public function setUsername(string $username) {
        $this->username = $username;
    }

    public function setMessageFrom(string $messageFrom) {
        $this->messageFrom = $messageFrom;
    }

    public function setMessage(string $message) {
        $this->message = $message;
    }

    public function getID(): int {
        return $this->id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getMessageFrom(): string {
        return $this->messageFrom;
    }

    public function getMessage(): string {
        return $this->message;
    }
    
	/**
	 * Specify data which should be serialized to JSON
	 * Serializes the object to a value that can be serialized natively by json_encode().
	 *
	 * @return mixed Returns data which can be serialized by json_encode(), which is a value of any type other than a resource .
	 */
	function jsonSerialize() {
        $vars = get_object_vars($this);

        return $vars;
	}
}